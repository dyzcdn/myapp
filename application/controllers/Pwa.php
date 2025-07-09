<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pwa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->config->load('self_config');
    }

    public function manifest() {
        $site_name   = $this->config->item('site_name');
        // $site_slogan = $this->config->item('site_slogan');
        $base_url    = base_url();

        $manifest = [
            'name' => $site_name,
            'short_name' => $site_name,
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#0d6efd',
            'icons' => [
                [
                    'src' => $base_url . 'favicon-192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png'
                ],
                [
                    'src' => $base_url . 'favicon-512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png'
                ]
            ]
        ];

        header('Content-Type: application/json');
        echo json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function service_worker() {
        header('Content-Type: application/javascript');

        echo <<<JS
self.addEventListener('install', event => {
    console.log('Service Worker installing.');
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    console.log('Service Worker activated.');
});

self.addEventListener('fetch', event => {
    event.respondWith(fetch(event.request));
});
JS;
    }
}
