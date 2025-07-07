
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Google OAuth
// $config['google_client_id'] = 'ISI_DENGAN_CLIENT_ID';
// $config['google_client_secret'] = 'ISI_DENGAN_CLIENT_SECRET';

// Cloudflare Turnstile
// $config['cf_turnstile_site_key'] = 'ISI_DENGAN_SITE_KEY';
// $config['cf_turnstile_secret_key'] = 'ISI_DENGAN_SECRET_KEY';

// SMTP Email
// $config['smtp_host'] = 'smtp.gmail.com';
// $config['smtp_port'] = 587;
// $config['smtp_user'] = 'emailkamu@gmail.com';
// $config['smtp_pass'] = 'password_smtp_kamu';


$config['site_name'] = 'DyzulkDev';
$config['site_slogan'] = 'Situs solusi digital terbaik';

// Google OAuth
$config['google_client_id'] = '863884067420-1gimj1b1c2a17qubmedfmc874a0rrmdq.apps.googleusercontent.com';
$config['google_client_secret'] = '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';

// Cloudflare Turnstile
$config['cf_turnstile_site_key'] = '0x4AAAAAABkB5TIwHVHUgpdb';
$config['cf_turnstile_secret_key'] = '0x4AAAAAABkB5fpcNcGBwDj0PcfAc57m2YA';

// SMTP Email

// $config['smtp_host'] = 'smtp.gmail.com';
// $config['smtp_port'] = 587;
// $config['smtp_user'] = 'smtp.nihonbuzz@gmail.com';
// $config['smtp_pass'] = 'tbszlmwkoajlrrex';
// $config['smtp_pass'] = 'bikiboclhmeyrubc';
// $config['smtp_crypto'] = 'tls';
// $config['smtp_from'] = 'smtp.nihonbuzz@gmail.com';
$config['smtp_from_name'] = 'DyzulkDev';

$config['smtp_host'] = 'mail.dyzulk.com';
$config['smtp_port'] = 587;
$config['smtp_user'] = 'noreply@dyzulk.com';
$config['smtp_pass'] = '#PersibBandung1933';
$config['smtp_from'] = 'noreply@dyzulk.com';


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
