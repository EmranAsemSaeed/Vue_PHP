<?php
// Backend/app/Controllers/UserController.php

class UserController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    // الحصول على جميع المستخدمين
    public function index() {
        try {
            $users = $this->userModel->getAll();
            $this->response->json($users);
        } catch (Exception $e) {
            $this->response->json(['error' => 'Failed to fetch users'], 500);
        }
    }

    // الحصول على مستخدم محدد
    public function show($id) {
        try {
            $user = $this->userModel->getById($id);
            
            if ($user) {
                $this->response->json($user);
            } else {
                $this->response->json(['error' => 'User not found'], 404);
            }
        } catch (Exception $e) {
            $this->response->json(['error' => 'Failed to fetch user'], 500);
        }
    }

    // إنشاء مستخدم جديد
    public function store() {
        try {
            $data = $this->getRequestData();
            
            // التحقق من الحقول المطلوبة
            if (!$this->validateRequiredFields($data, ['name', 'email', 'password'])) {
                return;
            }

            // التحقق من صحة البريد الإلكتروني
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->response->json(['error' => 'Invalid email format'], 400);
                return;
            }

            // التحقق إذا كان البريد الإلكتروني موجود مسبقاً
            if ($this->userModel->findByEmail($data['email'])) {
                $this->response->json(['error' => 'Email already exists'], 409);
                return;
            }

            // تشفير كلمة المرور
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']); // إزالة كلمة المرور الأصلية

            $id = $this->userModel->create($data);
            $this->response->json(['id' => $id, 'message' => 'User created successfully'], 201);
            
        } catch (Exception $e) {
            $this->response->json(['error' => 'Failed to create user'], 500);
        }
    }

    // تحديث بيانات المستخدم
    public function update($id) {
        try {
            $data = $this->getRequestData();
            
            // التحقق إذا كان المستخدم موجود
            $existingUser = $this->userModel->getById($id);
            if (!$existingUser) {
                $this->response->json(['error' => 'User not found'], 404);
                return;
            }

            // إذا كان هناك بريد إلكتروني جديد، التحقق من أنه غير مستخدم
            if (isset($data['email']) && $data['email'] !== $existingUser['email']) {
                if ($this->userModel->findByEmail($data['email'])) {
                    $this->response->json(['error' => 'Email already exists'], 409);
                    return;
                }
            }

            // إذا كانت هناك كلمة مرور جديدة، تشفيرها
            if (isset($data['password'])) {
                $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
                unset($data['password']);
            }

            $result = $this->userModel->update($id, $data);
            
            if ($result) {
                $this->response->json(['message' => 'User updated successfully']);
            } else {
                $this->response->json(['error' => 'Failed to update user'], 500);
            }
            
        } catch (Exception $e) {
            $this->response->json(['error' => 'Failed to update user'], 500);
        }
    }

    // حذف مستخدم
    public function delete($id) {
        try {
            // التحقق إذا كان المستخدم موجود
            $existingUser = $this->userModel->getById($id);
            if (!$existingUser) {
                $this->response->json(['error' => 'User not found'], 404);
                return;
            }

            $result = $this->userModel->delete($id);
            
            if ($result) {
                $this->response->json(['message' => 'User deleted successfully']);
            } else {
                $this->response->json(['error' => 'Failed to delete user'], 500);
            }
            
        } catch (Exception $e) {
            $this->response->json(['error' => 'Failed to delete user'], 500);
        }
    }

    // الحصول على إحصائيات المستخدمين
    public function stats() {
        try {
            $stats = $this->userModel->getStats();
            $this->response->json($stats);
        } catch (Exception $e) {
            $this->response->json(['error' => 'Failed to fetch user statistics'], 500);
        }
    }
}
?>