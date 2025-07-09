<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        $CI =& get_instance();
        return $CI->session->userdata('user') ? true : false;
    }
}

if (!function_exists('sanitize_username')) {
    function sanitize_username($input) {
        if (strpos($input, '@') !== false) {
            $input = explode('@', $input)[0];
        }

        return preg_replace('/[^a-zA-Z0-9_.-]/', '', strtolower($input));
    }
}