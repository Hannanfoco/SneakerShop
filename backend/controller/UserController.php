<?php
require_once __DIR__ . '/../business/UserBusinessLogic.php';

class UserController {
    private $logic;

    public function __construct() {
        $this->logic = new UserBusinessLogic();
    }

    public function getUser() {
        $query = Flight::request()->query;

        if (isset($query['id'])) {
            $user = $this->logic->getUserById((int)$query['id']);
            Flight::json(['success' => true, 'user' => $user]);
        } elseif (isset($query['email'])) {
            $user = $this->logic->getUserByEmail($query['email']);
            Flight::json(['success' => true, 'user' => $user]);
        } else {
            Flight::json(['success' => false, 'message' => 'Missing user ID or email'], 400);
        }
    }

    public function registerOrLogin() {
        $data = Flight::request()->data->getData();

        if (!isset($data['email']) || !isset($data['username'])) {
            Flight::json(['success' => false, 'message' => 'Missing required fields (email, username)'], 400);
            return;
        }

        try {
            $result = $this->logic->registerOrLoginUser($data);
            $message = $result['status'] === 'login' ? 'User logged in' : 'User registered and logged in';

            Flight::json([
                'success' => true,
                'message' => $message,
                'user' => $result['user']
            ]);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function getAllUsers() {
        Flight::json($this->logic->getAll()); // ✅ RIGHT
    }
    
    

    public function getUsers() {
        $params = Flight::request()->query->getData();
        $user = null;
    
        if (isset($params['id'])) {
            $user = $this->logic->getUserById($params['id']);
        } else if (isset($params['email'])) {
            $user = $this->logic->getUserByEmail($params['email']);
        }
    
        if ($user) {
            Flight::json(['success' => true, 'user' => $user]);
        } else {
            Flight::halt(404, 'User not found');
        }
    }
    
    

    public function updateUser() {
        $data = Flight::request()->data->getData();

        if (!isset($data['id'])) {
            Flight::json(['success' => false, 'message' => 'User ID is required for update'], 400);
            return;
        }

        try {
            $updated = $this->logic->updateProfile((int)$data['id'], $data);
            Flight::json(['success' => true, 'message' => 'Profile updated', 'updated' => $updated]);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function deleteUser() {
        $userId = Flight::request()->query['id'] ?? null;

        if (!$userId) {
            Flight::json(['success' => false, 'message' => 'Missing user ID to delete'], 400);
            return;
        }

        try {
            $this->logic->deleteUser((int)$userId);
            Flight::json(['success' => true, 'message' => "User ID $userId deleted"]);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
