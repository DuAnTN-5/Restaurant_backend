<?php

return [
    'client_id' => env('PAYPAL_CLIENT_ID'), // Lấy từ file .env
    'secret' => env('PAYPAL_SECRET'),       // Lấy từ file .env

    'settings' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'), // Chế độ sandbox hoặc live
        'http.ConnectionTimeOut' => 30,          // Thời gian chờ kết nối
        'log.LogEnabled' => true,                // Bật ghi log
        'log.FileName' => storage_path('logs/paypal.log'), // Đường dẫn tới file log
        'log.LogLevel' => 'ERROR'                // Mức ghi log: DEBUG, INFO, WARN, ERROR
    ],
];
