<?php

Flight::group('/auth', function () {

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register a new user",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="demo@gmail.com"),
     *             @OA\Property(property="password", type="string", example="some_password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    Flight::route('POST /register', function () {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, Authentication");

        $data = json_decode(file_get_contents('php://input'), true);
        $response = Flight::auth_service()->register($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User registered successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(500, $response['error']);
        }
    });

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login a user",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="demo@gmail.com"),
     *             @OA\Property(property="password", type="string", example="some_password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Login failed"
     *     )
     * )
     */
    Flight::route('POST /login', function () {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, Authentication");

        $data = json_decode(file_get_contents('php://input'), true);
        $response = Flight::auth_service()->login($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User logged in successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(500, $response['error']);
        }
    });

});
