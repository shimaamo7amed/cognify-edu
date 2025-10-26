<?php
if (!function_exists('apiResponse')) {
    function apiResponse($success, $data = [], $messages = '', $code = 200)
    {
        // تحقق إن كانت الرسالة مفتاح ترجمة أم لا
        $translatedMessage = is_string($messages) && !is_numeric($messages) && !empty($messages)
            ? __($messages)
            : $messages;

        return response()->json([
            'success' => $success,
            'message' => $translatedMessage,
            'data' => $data,
        ], $code);
    }

}