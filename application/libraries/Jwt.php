<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jwt {

    private $key = 'secret123';

    public function encode($data) {
        return base64_encode(json_encode($data));
    }

    public function decode($token) {
        return json_decode(base64_decode($token));
    }
}
