<?php
// backend/app/Controllers/AuthController.php

// require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Core/Controller.php';
class AuthController extends Controller
{
    public function showLogin(): void
    {
        // If the user is already logged in, redirect to the home page
        if (!empty($_SESSION['user'])) {
            $this->redirect('');
        }
        $this->view('auth/login', ['title' => 'Login']);
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $errors = [];

        // Validate email and password
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }
        if ($password === '') {
            $errors[] = 'Password is required.';
        }

        // If there are errors, re-display the login page
        if ($errors) {
            $this->view('auth/login', [
                'errors' => $errors,
                'old' => ['email' => $email]
            ]);
            return;
        }

        // Check the user in the database
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->view('auth/login', [
                'errors' => ['Email or password is incorrect.'],
                'old' => ['email' => $email]
            ]);
            return;
        }

        // Successful login
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
        ];

        // Redirect after login
        $to = !empty($_SESSION['intended']) ? ltrim((string)$_SESSION['intended'], '/') : '';
        unset($_SESSION['intended']);
        $this->redirect($to);
    }

    public function logout(): void
    {
        // End the session
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        $this->redirect('login');
    }
    
    // Helper function for rendering views
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . "/../Views/{$view}.php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // If the view doesn't exist, return a JSON response (for API)
            $this->response->json(['error' => 'View not found'], 404);
        }
    }
}
?>
