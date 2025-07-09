<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* * Self Configuration File
 * This file contains configuration settings for the application.
 * Make sure to fill in the required fields with your own values.
 */

$config['site_name'] = 'DyzulkDev';
$config['site_slogan'] = 'Situs solusi digital terbaik';

$config['google_client_id'] = 'ISI_DENGAN_CLIENT_ID';
$config['google_client_secret'] = 'ISI_DENGAN_CLIENT_SECRET';

// Cloudflare Turnstile
$config['cf_turnstile_site_key'] = 'ISI_DENGAN_SITE_KEY';
$config['cf_turnstile_secret_key'] = 'ISI_DENGAN_SECRET_KEY';

// SMTP Email
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_port'] = 587;
$config['smtp_user'] = 'emailkamu@gmail.com';
$config['smtp_pass'] = 'password_smtp_kamu';
$config['smtp_from'] = 'emailkamu@gmail.com';
$config['smtp_crypto'] = 'tls';
$config['smtp_from_name'] = 'DyzulkDev';

$config['site_logo_path'] = FCPATH . 'assets/img/logo/favicon.png';

/* Self-signed Certificate Authority Configuration
 * This configuration is used for generating self-signed certificates.
 * Make sure to adjust the paths and details according to your environment.
 */

$config['ca_root_dn'] = [
    "countryName"            => "ID",
    "organizationName"       => "DyzulkDev Certificate Authority Corp.",
    "organizationalUnitName" => "www.dyzulk.com",
    "commonName"             => "DyzulkDev"
];

$config['ca_intermediate_dn'] = [
    "countryName"      => "ID",
    "organizationName" => "DyLab Certificate Authority Inc.",
    "commonName"       => "DyLab Infinity CA1"
];

$config['default_leaf_dn'] = [
    'organization' => 'MyLab.',
    'locality'     => 'Jakarta',
    'state'        => 'DKI Jakarta',
    'country'      => 'ID'
];
