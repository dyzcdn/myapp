<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Favicon extends CI_Controller {

    private $source_path;

    public function __construct() {
        parent::__construct();
        $this->config->load('self_config');
        $this->source_path = $this->config->item('site_logo_path');
    }

    public function index($size = 32) {
        $size = (int) $size;

        if (!file_exists($this->source_path) || $size < 8 || $size > 512) {
            show_404();
        }

        // Hapus semua buffer output & header agar tidak korup
        header_remove();
        if (ob_get_length()) ob_clean();

        $src = @imagecreatefrompng($this->source_path);
        if (!$src) {
            show_error('Gagal membaca PNG dari: ' . $this->source_path);
        }

        header('Content-Type: image/png');
        header('Cache-Control: public, max-age=604800');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 604800) . ' GMT');

        $width = imagesx($src);
        $height = imagesy($src);
        $min = min($width, $height);

        $crop_x = (int)(($width - $min) / 2);
        $crop_y = (int)(($height - $min) / 2);

        $square = imagecreatetruecolor($min, $min);
        imagealphablending($square, false);
        imagesavealpha($square, true);

        imagecopy($square, $src, 0, 0, $crop_x, $crop_y, $min, $min);

        $resized = imagecreatetruecolor($size, $size);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled($resized, $square, 0, 0, 0, 0, $size, $size, $min, $min);

        imagepng($resized);

        imagedestroy($src);
        imagedestroy($square);
        imagedestroy($resized);
    }
}
