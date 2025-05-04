<?php



// Show full errors in browser and Postman
ini_set(option: 'display_errors', value: 1);
ini_set(option: 'display_startup_errors', value: 1);
error_reporting(error_level: E_ALL);
require_once '../service/UserService.php';


header('Content-Type: application/json');

$userService = new UserService();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // 🔹 GET user by ID or email
    case 'GET':
        if (isset($_GET['id'])) {
            $user = $userService->getById((int)$_GET['id']);
            echo json_encode([
                'success' => true,
                'user' => $user
            ]);
        } else if (isset($_GET['email'])) {
            $user = $userService->getUserByEmail($_GET['email']);
            echo json_encode([
                'success' => true,
                'user' => $user
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Missing user ID or email'
            ]);
        }
        break;

    // 🔹 POST - Register/Login user
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email']) || !isset($data['username'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields (email, username)'
            ]);
            exit;
        }

        $existingUser = $userService->getUserByEmail($data['email']);

        if ($existingUser) {
            echo json_encode([
                'success' => true,
                'message' => 'User logged in',
                'user' => $existingUser
            ]);
        } else {
            // Register new user
            $data['password_hash'] = ''; // or hash if you're using passwords
            $data['role'] = 'user'; // default role
            $newUser = $userService->registerUser($data);

            echo json_encode([
                'success' => true,
                'message' => 'User registered and logged in',
                'user' => $newUser
            ]);
        }
        break;

    // 🔹 PUT - Update user profile
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'User ID is required for update'
            ]);
            exit;
        }

        $updated = $userService->updateProfile((int)$data['id'], $data);

        echo json_encode([
            'success' => true,
            'message' => 'Profile updated',
            'updated' => $updated
        ]);
        break;

    // 🔹 DELETE - Admin delete user
    case 'DELETE':
        if (!isset($_GET['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing user ID to delete'
            ]);
            exit;
        }

        $userIdToDelete = (int)$_GET['id'];

        // You can add a check here to ensure only admins can delete
        $userService->delete($userIdToDelete);

        echo json_encode([
            'success' => true,
            'message' => "User ID $userIdToDelete deleted"
        ]);
        break;

    //  Unsupported method
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method Not Allowed'
        ]);
}
