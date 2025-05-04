<?php
require_once __DIR__ . '/../service/UserService.php';

class UserBusinessLogic {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function getUserById($id) {
        return $this->userService->getById($id);
    }

    public function getUserByEmail($email) {
        return $this->userService->getUserByEmail($email);
    }


    public function registerOrLoginUser($data) {
        $existingUser = $this->userService->getUserByEmail($data['email']);

        if ($existingUser) {
            return [
                'status' => 'login',
                'user' => $existingUser
            ];
        } else {
            $data['role'] = $data['role'] ?? 'customer'; // ✅ Default to valid role
            $newUser = $this->userService->registerUser($data);

            return [
                'status' => 'register',
                'user' => $newUser
            ];
        }
    }


    public function updateProfile($userId, $data) {
        return $this->userService->updateProfile($userId, $data);
    }

    public function deleteUser($userId) {
        return $this->userService->delete($userId);
    }
}
