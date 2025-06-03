<?php

// Show all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoload dependencies
require_once __DIR__ . '/vendor/autoload.php';

// Load configuration, roles, middleware, services
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data/roles.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/service/AuthService.php';

// Register services
Flight::register('auth_service', 'AuthService');
Flight::register('auth_middleware', 'AuthMiddleware');

// 🔒 Global auth middleware — protects all routes except public ones
Flight::route('/*', function () {
    $url = Flight::request()->url;

    // Publicly accessible routes
    if (
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0 ||
        strpos($url, '/docs') === 0
    ) {
        return true;
    }

    try {
        // Get token from Authorization header
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            Flight::halt(401, 'Missing or malformed Authorization header');
        }

        $token = substr($authHeader, 7); // Remove "Bearer "
        Flight::auth_middleware()->verifyToken($token);

        return true; // ✅ Ensure route execution continues

    } catch (Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});

// Load all route files
require_once __DIR__ . '/routes/AuthRoutes.php';
require_once __DIR__ . '/routes/UserRoutes.php';
require_once __DIR__ . '/routes/OrderRoutes.php';
require_once __DIR__ . '/routes/CartRoutes.php';
require_once __DIR__ . '/routes/FavouriteRoutes.php';
require_once __DIR__ . '/routes/PaymentRoutes.php';
require_once __DIR__ . '/routes/ProductRoutes.php';

// Start FlightPHP
Flight::start();
