<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('Jwt');
        $this->load->helper('response');
    }

    public function login() {

        // Read POST data
        $email    = $this->input->post('email');
        $password = $this->input->post('password');

        // Basic validation
        if (!$email || !$password) {
            json_error('Email and password are required', 422);
        }

        // Find user
        $user = $this->User_model->getByEmail($email);

        if (!$user) {
            json_error('Invalid credentials', 401);
        }

        // Verify password
        if (!password_verify($password, $user->password)) {
            json_error('Invalid credentials', 401);
        }

        // Create JWT payload
        $payload = [
            'user_id' => $user->id,
            'role'    => $user->role,
            'iat'     => time(),
            'exp'     => time() + (60 * 60) // 1 hour
        ];

        $token = $this->jwt->encode($payload);

        // Success response
        json_response([
            'success' => true,
            'token'   => $token
        ]);
    }
}
