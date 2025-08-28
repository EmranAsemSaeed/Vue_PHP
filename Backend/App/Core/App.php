<?php
// backend/config.php

class Config {
    public static function getConfig() {
        return [
            'database' => [
                'host' => getenv('DB_HOST') ?: 'localhost',
                'dbname' => getenv('DB_NAME') ?: 'volunteer_db',
                'username' => getenv('DB_USER') ?: 'root',
                'password' => getenv('DB_PASS') ?: '',
                'charset' => 'utf8mb4'
            ],
            'app' => [
                'base_url' => getenv('APP_URL') ?: 'http://localhost:8000',
                'env' => getenv('APP_ENV') ?: 'development',
                'debug' => getenv('APP_DEBUG') ?: true
            ]
        ];
    }
    
    public static function getCorsHeaders() {
        return [
            "Access-Control-Allow-Origin: *",
            "Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS",
            "Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept, charset, boundary, Content-Length",
            "Access-Control-Allow-Credentials: true",
            "Content-Type: application/json; charset=UTF-8"
        ];
    }
}
?>