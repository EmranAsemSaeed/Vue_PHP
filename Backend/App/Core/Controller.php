<?php
// backend/app/Controllers/Controller.php

class Controller {
    protected $response;
    
    public function __construct() {
        $this->response = new Response();
    }
    
    protected function getRequestData() {
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?? [];
    }
        protected function redirect(string $path): void
    {
        header("Location: /{$path}");
        exit;
    }
    protected function validateRequiredFields($data, $requiredFields) {
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missingFields[] = $field;
            }
        }
        
        if (!empty($missingFields)) {
            $this->response->json([
                'error' => 'Missing required fields',
                'fields' => $missingFields
            ], 400);
            return false;
        }
        
        return true;
    }
}
?>