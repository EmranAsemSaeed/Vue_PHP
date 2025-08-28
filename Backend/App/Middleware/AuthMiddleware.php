<?php
namespace App\Middleware;

use App\Core\App;

final class AuthMiddleware
{
    public function handle(): void
    {
        if (empty($_SESSION['user'])) {
            // Save intended URL (optional)
            $_SESSION['intended'] = $_SERVER['REQUEST_URI'] ?? '/';
            App::redirect('login');
        }
    }
}
