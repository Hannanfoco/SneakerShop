<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

/**
 * @property UserDao $dao
 */
class UserService extends BaseService {
    protected $userDao;

    public function __construct() {
        $this->userDao = new UserDao(); 
        parent::__construct($this->userDao);
    }

    /**
     * Register a new user with password hashing
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function registerUser($data): mixed {
        $this->validateRequiredFields($data, ['username', 'email', 'password', 'role']);

        $existing = $this->userDao->getByEmail($data['email']);
        if ($existing) {
            throw new Exception("User with this email already exists.");
        }

        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']); 

        return $this->userDao->insert($data);
    }

    /**
     * Authenticate user (login)
     *
     * @param string $email
     * @param string $password
     * @return mixed
     * @throws Exception
     */
    public function login($email, $password): mixed {
        $user = $this->userDao->getByEmail($email);

        if (!$user) {
            throw new Exception("User not found.");
        }

        if (!password_verify($password, $user['password_hash'])) {
            throw new Exception("Invalid credentials.");
        }

        return $user;
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return mixed
     */
    public function getUserByEmail($email): mixed {
        return $this->userDao->getByEmail($email);
    }

    /**
     * Check if user is admin
     *
     * @param array $user
     * @return bool
     */
    public function isAdmin($user): bool {
        return $user && isset($user['role']) && $user['role'] === 'admin';
    }

    /**
     * Update user profile
     *
     * @param int $userId
     * @param array $data
     * @return mixed
     */
    public function updateProfile($userId, $data): mixed {
        return $this->userDao->update($userId, $data);
    }

    public function getStats() {
        return $this->dao->getStats();
    }
}
