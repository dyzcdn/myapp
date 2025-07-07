<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('set_flash')) {
    function set_flash($type, $message) {
        $CI =& get_instance();
        $CI->session->set_flashdata('flash', ['type' => $type, 'message' => $message]);
    }
}

if (!function_exists('display_flash')) {
    function display_flash() {
        $CI =& get_instance();
        $flash = $CI->session->flashdata('flash');

        if ($flash) {
            $alert_type = [
                'success' => 'alert-success',
                'error'   => 'alert-danger',
                'warning' => 'alert-warning',
                'info'    => 'alert-info'
            ];

            $type = isset($alert_type[$flash['type']]) ? $alert_type[$flash['type']] : 'alert-info';

            return '<div class="alert ' . $type . ' alert-dismissible fade show" role="alert">'
                . $flash['message'] .
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }

        return '';
    }
}


if (!function_exists('flash_message')) {
    function flash_message() {
        $CI =& get_instance();
        $output = '';

        foreach (['success', 'error', 'warning', 'info'] as $type) {
            if ($msg = $CI->session->flashdata($type)) {
                $bootstrapClass = [
                    'success' => 'success',
                    'error' => 'danger',
                    'warning' => 'warning',
                    'info' => 'info'
                ][$type];

                $output .= '<div class="alert alert-' . $bootstrapClass . ' alert-dismissible fade show" role="alert">';
                $output .= $msg; // Izinkan HTML
                $output .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                $output .= '</div>';
            }
        }

        return $output;
    }
}

