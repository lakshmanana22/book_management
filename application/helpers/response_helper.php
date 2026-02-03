<?php
function json_response($data, $code = 200) {
    $CI =& get_instance();
    $CI->output
        ->set_status_header($code)
        ->set_content_type('application/json')
        ->set_output(json_encode($data))
        ->_display();
    exit;
}

function json_error($message, $code = 400) {
    json_response([
        'success' => false,
        'message' => $message
    ], $code);
}
