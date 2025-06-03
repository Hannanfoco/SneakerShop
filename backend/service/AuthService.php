<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
require_once __DIR__ . '/../config.php'; // Ensure Config::JWT_SECRET() is available
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;

class AuthService extends BaseService {
    private $auth_dao;

    public function __construct() {
        $this->auth_dao = new AuthDao();
        parent::__construct($this->auth_dao);
    }

    public function get_user_by_email($email) {
        return $this->auth_dao->get_user_by_email($email);
    }

    public function register($entity) {
        // Force cast to array (just in case)
        $entity = (array)$entity;
    
        // Debug: Log to error_log to verify type if needed
        // error_log(print_r($entity, true));
    
        if (!is_array($entity)) {
            return [
                'success' => false,
                'error' => 'Invalid request data format.'
            ];
        }
    
        if (empty($entity['email']) || empty($entity['password'])) {
            return [
                'success' => false,
                'error' => 'Email and password are required.'
            ];
        }
    
        $existing = $this->auth_dao->get_user_by_email($entity['email']);
        if ($existing) {
            return [
                'success' => false,
                'error' => 'Email already registered.'
            ];
        }
    
        $entity['password_hash'] = password_hash($entity['password'], PASSWORD_BCRYPT);
    
        // ✅ Defensive: Only unset if key exists
        if (array_key_exists('password', $entity)) {
            unset($entity['password']);
        }
    
        $entity['role'] = 'customer';
        $entity['created_at'] = date('Y-m-d H:i:s');
    
        $insertedUser = $this->auth_dao->insert($entity);
    
        // Optional: Remove sensitive data
        if (is_array($insertedUser) && array_key_exists('password_hash', $insertedUser)) {
            unset($insertedUser['password_hash']);
        }
    
        return [
            'success' => true,
            'data' => $insertedUser
        ];
    }
    


    public function login($entity) {
        $entity = (array)$entity;

        if (empty($entity['email']) || empty($entity['password'])) {
            return [
                'success' => false,
                'error' => 'Email and password are required.'
            ];
        }

        $user = $this->auth_dao->get_user_by_email($entity['email']);
        if (!$user || !password_verify($entity['password'], $user['password_hash'])) {
            return [
                'success' => false,
                'error' => 'Invalid email or password.'
            ];
        }

        // Remove sensitive info
        unset($user['password_hash']);

        // Build JWT payload
        $payload = [
            'id'    => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role'  => $user['role'],
            'iat'   => time(),
            'exp'   => time() + (60 * 60 * 24) // valid for 24 hours
        ];

        // Encode the token
        $token = JWT::encode($payload, Config::JWT_SECRET(), 'HS256');

        return [
            'success' => true,
            'data' => array_merge($user, ['token' => $token])
        ];
    }
}
