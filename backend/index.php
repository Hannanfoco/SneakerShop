<?php

// Show all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// Load all routes (this includes OrderController and route definitions)
require_once __DIR__ . '/routes/OrderRoutes.php';
require_once __DIR__ . '/routes/CartRoutes.php';
require_once __DIR__ . '/routes/FavouriteRoutes.php';
require_once __DIR__ . '/routes/PaymentRoutes.php';
require_once __DIR__ . '/routes/ProductRoutes.php';
require_once __DIR__ . '/routes/UserRoutes.php';






// Start the FlightPHP engine
Flight::start();
