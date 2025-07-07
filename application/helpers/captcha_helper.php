<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('turnstile_widget')) {
    function turnstile_widget($site_key, $theme = 'light') {
        return '<div class="cf-turnstile" data-sitekey="' . htmlspecialchars($site_key) . '" data-theme="' . $theme . '"></div>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
    }
}
