<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth {

    private $CI;
    public $user;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('Jwt');
        $this->CI->load->helper('response');
    }

    public function check() {

        $headers = $this->CI->input->request_headers();

        if (
            !isset($headers['Authorization']) &&
            !isset($headers['authorization'])
        ) {
            json_error('Authorization token required', 401);
        }

        $authHeader = $headers['Authorization'] ?? $headers['authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        if (!$token) {
            json_error('Token missing', 401);
        }

        $this->user = $this->CI->jwt->decode($token);

        if (!$this->user || !isset($this->user->user_id)) {
            json_error('Invalid token', 401);
        }
    }


    public function requireRole($role) {
        if ($this->user->role !== $role) {
            json_error('Forbidden', 403);
        }
    }
}
