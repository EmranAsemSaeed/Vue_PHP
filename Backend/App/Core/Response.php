<?php
// backend/app/Core/Response.php

class Response {
    public function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    public function send($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo $data;
        exit;
    }
}
?>