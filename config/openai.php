<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'), // Optional
    'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'), // Default model
    'temperature' => env('OPENAI_TEMPERATURE', 0.7), // Default temperature
    'max_tokens' => env('OPENAI_MAX_TOKENS', 100), // Default max tokens
    'top_p' => env('OPENAI_TOP_P', 1.0), // Default top_p
    'frequency_penalty' => env('OPENAI_FREQUENCY_PENALTY', 0.0), // Default frequency penalty
    'presence_penalty' => env('OPENAI_PRESENCE_PENALTY', 0.0), // Default presence penalty
    'stop' => env('OPENAI_STOP', null), // Default stop sequence
    'timeout' => env('OPENAI_TIMEOUT', 30), // Default timeout in seconds
    'retry' => [
        'max_attempts' => env('OPENAI_RETRY_MAX_ATTEMPTS', 3), // Default max attempts
        'delay' => env('OPENAI_RETRY_DELAY', 1000), // Default delay in milliseconds
        'multiplier' => env('OPENAI_RETRY_MULTIPLIER', 2), // Default multiplier
        'max_delay' => env('OPENAI_RETRY_MAX_DELAY', 60000), // Default max delay in milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Base URL
    |--------------------------------------------------------------------------
    |
    | If you are using a custom OpenAI API base URL, you can specify it here.
    | Otherwise, the default OpenAI API base URL will be used.
    */

    'api_base' => env('OPENAI_API_BASE', 'https://api.openai.com/v1'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Version
    |--------------------------------------------------------------------------
    |
    | Specify the version of the OpenAI API you want to use. This is useful
    | if you want to ensure compatibility with a specific version of the API.
    */

    'api_version' => env('OPENAI_API_VERSION', 'v1'),
];
