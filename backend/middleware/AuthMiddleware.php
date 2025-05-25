<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    /**
     * Verifies the JWT token and extracts the user.
     * Halts with 401 if missing or invalid.
     */
    public function verifyToken($token){
        if (!$token) {
            Flight::halt(401, "Missing authentication token");
        }

        try {
            $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

            // Make decoded token available throughout app
            Flight::set('user', $decoded_token);
            Flight::set('jwt_token', $token);
        } catch (Exception $e) {
            Flight::halt(401, "Invalid token: " . $e->getMessage());
        }

        return true;
    }

    /**
     * Checks if the user has the required role.
     * Example: 'admin' or 'customer'
     */
    public function authorizeRole($requiredRole) {
        $user = Flight::get('user');
        if (!$user || !isset($user->role) || $user->role !== $requiredRole) {
            Flight::halt(403, 'Access denied: insufficient privileges for role ' . $requiredRole);
        }
    }

    /**
     * Allows any role from an allowed list (e.g. ['admin', 'customer'])
     */
    public function authorizeRoles($roles = []) {
        $user = Flight::get('user');
        if (!$user || !isset($user->role) || !in_array($user->role, $roles)) {
            Flight::halt(403, 'Access denied: role not allowed');
        }
    }

    public function getCurrentUser($token) {
        $decoded = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
        
        return [
            'id' => $decoded->id ?? null,
            'username' => $decoded->username ?? null,
            'email' => $decoded->email ?? null,
            'role' => $decoded->role ?? null
        ];
    }

    /**
     * Optional: Checks for permission-based access (if implemented in your JWT)
     */
    public function authorizePermission($permission) {
        $user = Flight::get('user');
        if (
            !$user ||
            !isset($user->permissions) ||
            !in_array($permission, $user->permissions)
        ) {
            Flight::halt(403, 'Access denied: permission "' . $permission . '" is missing');
        }
    }
}
