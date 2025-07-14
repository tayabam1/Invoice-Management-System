<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use OpenAI\Client as OpenAIClient;
use OpenAI;
use Illuminate\Support\Facades\Config;
use OpenAI\Exceptions\ErrorException as OpenAIErrorException;
use Illuminate\Support\Facades\Http; // Import Laravel Http facade
use Illuminate\Http\Client\RequestException; // Import exception for Http facade
use Illuminate\Support\Arr; // Import Arr facade
use Symfony\Component\HttpFoundation\StreamedResponse; // Add StreamedResponse

class AiController extends Controller
{
    /**
     * Generate a description based on a prompt using the configured AI service.
     * Always returns JSON response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateDescription(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'prompt' => 'required|string|max:500',
                'locale' => 'required|string|max:10',
            ]);

            $userPrompt = $validated['prompt'];
            $locale = $validated['locale'];

            $provider = Config::get('services.ai.provider', 'openai');
            Log::info("Using AI provider: {$provider}");

            $generatedText = null;

            // Use non-streaming methods for all providers
            if ($provider === 'openai') {
                $generatedText = $this->generateWithOpenAI($userPrompt, $locale); // Use non-streaming version
            } elseif ($provider === 'deepseek') {
                $generatedText = $this->generateWithDeepSeek($userPrompt, $locale);
            } elseif ($provider === 'gemini') {
                $generatedText = $this->generateWithGemini($userPrompt, $locale);
            } else {
                Log::error("Unsupported AI provider configured: {$provider}");
                return response()->json(['message' => 'Unsupported AI provider configured.'], 501);
            }

            if (empty($generatedText)) {
                Log::warning("AI provider '{$provider}' failed to generate a description for prompt: " . $userPrompt);
                return response()->json(['message' => 'AI failed to generate a description.'], 500);
            }

            Log::info("AI provider '{$provider}' generated description: {$generatedText}");
            $generatedText = trim($generatedText, " \n\r\t\v\0\"'");

            return response()->json(['description' => $generatedText]); // Always return JSON

        } catch (ValidationException $e) {
            Log::error('AI description generation validation failed: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json(['message' => 'Invalid input provided.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error generating AI description: ' . $e->getMessage());
            if ($e instanceof RequestException && $e->response) {
                Log::error('AI Service Response Body: ' . $e->response->body());
            }
            $statusCode = ($e instanceof RequestException && $e->response) ? $e->response->status() : 500;
            if ($statusCode < 400 || $statusCode >= 600) {
                $statusCode = 500;
            }
            $userMessage = 'An unexpected error occurred while generating the description. Please try again later.';
            if ($statusCode >= 500) {
                $userMessage = 'An error occurred while communicating with the AI service. Please check application logs for details.';
            } elseif ($statusCode === 401 || $statusCode === 403) {
                $userMessage = 'Authentication error with the AI service. Please check API key configuration.';
            } elseif ($statusCode === 429) {
                $userMessage = 'AI service rate limit exceeded. Please try again later.';
            }

            return response()->json(['message' => $userMessage], $statusCode);
        }
    }

    /**
     * Generate a description via streaming based on a prompt.
     *
     * @param Request $request
     * @return StreamedResponse|JsonResponse
     */
    public function generateDescriptionStream(Request $request): StreamedResponse|JsonResponse
    {
        Log::info('Attempting to start generateDescriptionStream.', ['url' => $request->fullUrl(), 'method' => $request->method()]);

        try {
            $validated = $request->validate([
                'prompt' => 'required|string|max:500',
                'locale' => 'required|string|max:10',
            ]);

            $userPrompt = $validated['prompt'];
            $locale = $validated['locale'];
            $provider = Config::get('services.ai.provider', 'openai');
            Log::info("Using AI provider for streaming: {$provider}");

            $response = new StreamedResponse(function () use ($provider, $userPrompt, $locale, $request) { // Pass $request for logging user
                // Set headers for Server-Sent Events
                header('Content-Type: text/event-stream');
                header('Cache-Control: no-cache');
                header('Connection: keep-alive');
                header('X-Accel-Buffering: no'); // Important for Nginx

                $streamMethod = null;
                if ($provider === 'openai') {
                    $streamMethod = 'generateWithOpenAIStream';
                } elseif ($provider === 'deepseek') {
                    // For DeepSeek, we'll simulate streaming by sending the full content as one event
                    // if true streaming isn't easily available with current setup.
                    $streamMethod = 'generateWithDeepSeekStream';
                } elseif ($provider === 'gemini') {
                    // Similar for Gemini
                    $streamMethod = 'generateWithGeminiStream';
                } else {
                    Log::error("Unsupported AI provider configured for streaming: {$provider}");
                    echo "event: error\ndata: " . json_encode(['message' => 'Unsupported AI provider configured.']) . "\n\n";
                    flush();
                    return;
                }

                try {
                    foreach ($this->{$streamMethod}($userPrompt, $locale) as $chunk) {
                        if (connection_aborted()) {
                            Log::info('Client disconnected during AI stream.', ['user_id' => $request->user()?->id]);
                            break;
                        }
                        echo "event: message\ndata: " . json_encode(['text' => $chunk]) . "\n\n";
                        flush();
                    }
                } catch (\Exception $e) {
                    Log::error("Error during AI stream for provider {$provider}: " . $e->getMessage(), [
                        'user_id' => $request->user()?->id, // Add user context if available
                        'prompt' => $userPrompt,
                        'locale' => $locale,
                        'exception_trace' => $e->getTraceAsString() // More detailed logging for stream errors
                    ]);
                    echo "event: error\ndata: " . json_encode(['message' => 'Error during AI generation stream. Please check logs.']) . "\n\n"; // Generic message to client
                    flush();
                } finally {
                    echo "event: end\ndata: " . json_encode(['message' => 'Stream ended.']) . "\n\n";
                    flush();
                }
            });

            return $response;

        } catch (ValidationException $e) {
            Log::error('AI stream generation validation failed: ' . $e->getMessage(), ['errors' => $e->errors(), 'user_id' => $request->user()?->id]);
            // For validation errors, we can't easily stream an error, so return JSON
            return response()->json(['message' => 'Invalid input provided.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error preparing AI stream: ' . $e->getMessage(), [
                'user_id' => $request->user()?->id,
                'exception_trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'An unexpected error occurred while preparing the stream.'], 500);
        }
    }

    /**
     * Generate description using OpenAI.
     *
     * @param string $userPrompt
     * @param string $locale
     * @return string|null
     * @throws OpenAIErrorException|\Exception
     */
    protected function generateWithOpenAI(string $userPrompt, string $locale): ?string
    {
        $apiKey = Config::get('services.openai.api_key');
        if (empty($apiKey)) {
            Log::error('OpenAI API key is not configured.');
            throw new \Exception('OpenAI API key is missing.');
        }

        $openai = OpenAI::client($apiKey, Config::get('services.openai.organization'));

        $systemPrompt = "You are an assistant helping create descriptions for online forms.";
        $fullPrompt = "Generate a concise and engaging form description in the language corresponding to locale code '{$locale}'. The form's title or main topic is: '{$userPrompt}'. Keep the description under 50 words.";

        try {
            $response = $openai->chat()->create([
                'model' => Config::get('services.openai.model', 'gpt-3.5-turbo'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $fullPrompt],
                ],
                'max_tokens' => Config::get('services.openai.max_tokens', 100),
                'temperature' => Config::get('services.openai.temperature', 0.7),
            ]);

            return $response->choices[0]->message->content ?? null;

        } catch (OpenAIErrorException $e) {
            $errorDetails = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'openai_error_type' => method_exists($e, 'getError') && $e->getError() ? $e->getError()['type'] ?? 'N/A' : 'N/A',
                'openai_error_code' => method_exists($e, 'getError') && $e->getError() ? $e->getError()['code'] ?? 'N/A' : 'N/A',
                'openai_param' => method_exists($e, 'getError') && $e->getError() ? $e->getError()['param'] ?? 'N/A' : 'N/A',
            ];
            Log::error('OpenAI API error: ', $errorDetails);
            throw new \Exception('OpenAI service error occurred.', $e->getCode(), $e);
        }
    }

    /**
     * Generate description using OpenAI (Streaming).
     *
     * @param string $userPrompt
     * @param string $locale
     * @return \Generator
     * @throws OpenAIErrorException|\Exception
     */
    protected function generateWithOpenAIStream(string $userPrompt, string $locale): \Generator
    {
        $apiKey = Config::get('services.openai.api_key');
        if (empty($apiKey)) {
            Log::error('OpenAI API key is not configured.');
            throw new \Exception('OpenAI API key is missing.');
        }
        $openai = OpenAI::client($apiKey, Config::get('services.openai.organization'));
        $systemPrompt = "You are an assistant helping create descriptions for online forms.";
        $fullPrompt = "Generate a concise and engaging form description in the language corresponding to locale code '{$locale}'. The form's title or main topic is: '{$userPrompt}'. Keep the description under 50 words.";

        try {
            $stream = $openai->chat()->createStreamed([
                'model' => Config::get('services.openai.model', 'gpt-3.5-turbo'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $fullPrompt],
                ],
                'max_tokens' => Config::get('services.openai.max_tokens', 100),
                'temperature' => Config::get('services.openai.temperature', 0.7),
            ]);

            foreach ($stream as $response) {
                $content = $response->choices[0]->delta->content;
                if ($content !== null) {
                    yield $content;
                }
            }
        } catch (OpenAIErrorException $e) {
            Log::error('OpenAI API stream error: ' . $e->getMessage());
            throw new \Exception('OpenAI service stream error occurred.', $e->getCode(), $e);
        }
    }

    /**
     * Generate description using DeepSeek.
     *
     * @param string $userPrompt
     * @param string $locale
     * @return string|null
     * @throws RequestException|\Exception
     */
    protected function generateWithDeepSeek(string $userPrompt, string $locale): ?string
    {
        $apiKey = Config::get('services.deepseek.api_key');
        $apiBase = Config::get('services.deepseek.api_base', 'https://api.deepseek.com');
        $model = Config::get('services.deepseek.model', 'deepseek-chat');
        $timeout = Config::get('services.deepseek.timeout', 30);

        if (empty($apiKey)) {
            Log::error('DeepSeek API key is not configured.');
            throw new \Exception('DeepSeek API key is missing.');
        }

        $systemPrompt = "You are an assistant helping create descriptions for online forms.";
        $fullPrompt = "Generate a concise and engaging form description in the language corresponding to locale code '{$locale}'. The form's title or main topic is: '{$userPrompt}'. Keep the description under 50 words.";

        $endpoint = rtrim($apiBase, '/') . '/chat/completions'; // Standard chat completions endpoint

        try {
            $response = Http::withToken($apiKey)
                ->timeout($timeout)
                ->post($endpoint, [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $fullPrompt],
                    ],
                    'max_tokens' => Config::get('services.deepseek.max_tokens', 100),
                    'temperature' => Config::get('services.deepseek.temperature', 0.7),
                ]);

            $response->throw(); // Throw an exception for 4xx/5xx responses

            return $response->json('choices.0.message.content');

        } catch (RequestException $e) {
            Log::error('DeepSeek API request error: ' . $e->getMessage(), [
                'status' => $e->response?->status(),
                'response' => $e->response?->body(),
            ]);
            throw new \Exception('DeepSeek service error occurred.', $e->getCode(), $e);
        }
    }

    /**
     * Generate description using DeepSeek (Simulated Streaming).
     *
     * @param string $userPrompt
     * @param string $locale
     * @return \Generator
     * @throws RequestException|\Exception
     */
    protected function generateWithDeepSeekStream(string $userPrompt, string $locale): \Generator
    {
        Log::info("DeepSeek: Using non-streaming fallback for stream request.");
        $fullContent = $this->generateWithDeepSeek($userPrompt, $locale);
        if ($fullContent !== null) {
            yield $fullContent;
        }
    }

    /**
     * Generate description using Google Gemini.
     *
     * @param string $userPrompt
     * @param string $locale
     * @return string|null
     * @throws RequestException|\Exception
     */
    protected function generateWithGemini(string $userPrompt, string $locale): ?string
    {
        $apiKey = Config::get('services.gemini.api_key');
        $apiBase = Config::get('services.gemini.api_base', 'https://generativelanguage.googleapis.com');
        $apiVersion = Config::get('services.gemini.api_version', 'v1beta');
        $model = Config::get('services.gemini.model', 'gemini-pro');
        $timeout = Config::get('services.gemini.timeout', 30);

        if (empty($apiKey)) {
            Log::error('Gemini API key is not configured.');
            throw new \Exception('Gemini API key is missing.');
        }

        $fullPrompt = "You are an assistant helping create descriptions for online forms. Generate a concise and engaging form description in the language corresponding to locale code '{$locale}'. The form's title or main topic is: '{$userPrompt}'. Keep the description under 50 words.";

        $endpoint = rtrim($apiBase, '/') . "/{$apiVersion}/models/{$model}:generateContent?key={$apiKey}";

        try {
            $response = Http::timeout($timeout)
                ->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $fullPrompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => Config::get('services.gemini.temperature', 0.7),
                        'maxOutputTokens' => Config::get('services.gemini.max_output_tokens', 150),
                    ],
                ]);

            $response->throw(); // Throw an exception for 4xx/5xx responses

            $generatedText = Arr::get($response->json(), 'candidates.0.content.parts.0.text');

            if (!$generatedText) {
                $generatedText = Arr::get($response->json(), 'promptFeedback.blockReason.message');
                if ($generatedText) {
                    Log::warning('Gemini generation blocked: ' . $generatedText);
                    throw new \Exception('Gemini generation blocked: ' . $generatedText);
                }
            }

            return $generatedText;

        } catch (RequestException $e) {
            Log::error('Gemini API request error: ' . $e->getMessage(), [
                'status' => $e->response?->status(),
                'response' => $e->response?->body(),
            ]);
            throw new \Exception('Gemini service error occurred.', $e->getCode(), $e);
        }
    }

    /**
     * Generate description using Google Gemini (Simulated Streaming).
     *
     * @param string $userPrompt
     * @param string $locale
     * @return \Generator
     * @throws RequestException|\Exception
     */
    protected function generateWithGeminiStream(string $userPrompt, string $locale): \Generator
    {
        Log::info("Gemini: Using non-streaming fallback for stream request.");
        $fullContent = $this->generateWithGemini($userPrompt, $locale);
        if ($fullContent !== null) {
            yield $fullContent;
        }
    }

    /**
     * Store feedback received for an AI generation.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeFeedback(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'prompt' => 'required|string|max:1000', // Increased max length
                'generated_text' => 'required|string|max:2000', // Increased max length
                'feedback' => 'required|string|in:good,bad', // Validate feedback value
                'locale' => 'required|string|max:10',
                'provider' => 'nullable|string|max:50', // Optional: Track which provider was used
            ]);

            // Log the feedback (replace with database storage or other processing as needed)
            Log::info('AI Feedback Received:', [
                'user_id' => $request->user()->id, // Assuming authenticated user
                'provider' => $validated['provider'] ?? Config::get('services.ai.provider', 'unknown'),
                'locale' => $validated['locale'],
                'prompt' => $validated['prompt'],
                'generated_text' => $validated['generated_text'],
                'feedback' => $validated['feedback'],
                'timestamp' => now()->toIso8601String(),
            ]);

            // You could store this in a database table:
            // AiFeedback::create([...]);

            return response()->json(['message' => 'Feedback received successfully.'], 200);

        } catch (ValidationException $e) {
            Log::error('AI feedback validation failed: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json(['message' => 'Invalid feedback data provided.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error storing AI feedback: ' . $e->getMessage());
            return response()->json(['message' => 'An unexpected error occurred while storing feedback.'], 500);
        }
    }
}
