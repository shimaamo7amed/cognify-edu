<?php


return [
    'FAWRY_URL' => env('FAWRY_URL', "https://atfawry.fawrystaging.com/"), //https://www.atfawry.com/ for production
    'FAWRY_SECRET' => env('FAWRY_SECRET'),
    'FAWRY_MERCHANT' => env('FAWRY_MERCHANT'),
    'FAWRY_DISPLAY_MODE' => env('FAWRY_DISPLAY_MODE', "POPUP"), //required allowed values [POPUP, INSIDE_PAGE, SIDE_PAGE , SEPARATED]
    'FAWRY_PAY_MODE' => env('FAWRY_PAY_MODE', "CARD"),
    'FAWRY_TIMEOUT' => env('FAWRY_TIMEOUT', 30), // Timeout in seconds for API requests
    'FAWRY_MAX_RETRIES' => env('FAWRY_MAX_RETRIES', 3), // Maximum number of retry attempts
    'FAWRY_CONNECTION_TIMEOUT' => env('FAWRY_CONNECTION_TIMEOUT', 10), // Connection timeout in seconds
    'FAWRY_READ_TIMEOUT' => env('FAWRY_READ_TIMEOUT', 30), // Read timeout in seconds
];
