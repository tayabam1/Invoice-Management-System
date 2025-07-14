<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ai' => [
        'provider' => env('AI_SERVICE_PROVIDER', 'openai'), // Default to openai
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
        'temperature' => (float) env('OPENAI_TEMPERATURE', 0.7),
        'max_tokens' => (int) env('OPENAI_MAX_TOKENS', 100),
        // Add other OpenAI params from your previous config/openai.php if desired
        'api_base' => env('OPENAI_API_BASE', 'https://api.openai.com/v1'),
        'api_version' => env('OPENAI_API_VERSION', 'v1'),
        'timeout' => env('OPENAI_TIMEOUT', 30),
    ],

    'deepseek' => [
        'api_key' => env('DEEPSEEK_API_KEY'),
        'api_base' => env('DEEPSEEK_API_BASE', 'https://api.deepseek.com'),
        'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'), // Or 'deepseek-coder' etc.
        'temperature' => (float) env('DEEPSEEK_TEMPERATURE', 0.7),
        'max_tokens' => (int) env('DEEPSEEK_MAX_TOKENS', 100), // Adjust default if needed
        'timeout' => env('DEEPSEEK_TIMEOUT', 30),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'api_base' => env('GEMINI_API_BASE', 'https://generativelanguage.googleapis.com'),
        'api_version' => env('GEMINI_API_VERSION', 'v1beta'),
        'model' => env('GEMINI_MODEL', 'gemini-pro'),
        'temperature' => (float) env('GEMINI_TEMPERATURE', 0.7),
        'max_output_tokens' => (int) env('GEMINI_MAX_TOKENS', 150), // Gemini often uses 'maxOutputTokens'
        'timeout' => (int) env('GEMINI_TIMEOUT', 30),
        // Add other Gemini specific params if needed (e.g., topP, topK)
    ],

];
