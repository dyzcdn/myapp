<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function get_profile_picture($user, $class, $size = 32) {
    $ci =& get_instance();

    if (!empty($user->profile_picture)) {
        $src = filter_var($user->profile_picture, FILTER_VALIDATE_URL)
            ? $user->profile_picture
            : base_url($user->profile_picture);
    } else {
        $src = base_url('assets/img/default-profile.png');
    }

    return '<img src="' . $src . '" class="' . $class . '" width="' . $size . '" height="' . $size . '" alt="Profile">';
}

if (!function_exists('read_config_file')) {
    function read_config_file($filepath) {
        if (!file_exists($filepath)) return false;

        $contents = file_get_contents($filepath);
        return highlight_string($contents, true);
    }
}
