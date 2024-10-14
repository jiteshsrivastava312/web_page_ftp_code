<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Example of server information fetching
// Fetching server information like PHP version, server software, etc.
$server_info = [
    'PHP Version' => phpversion(),
    'Server Software' => $_SERVER['SERVER_SOFTWARE'],
    'Document Root' => $_SERVER['DOCUMENT_ROOT'],
    'Server Name' => $_SERVER['SERVER_NAME'],
    'Server IP' => $_SERVER['SERVER_ADDR'],
    'Client IP' => $_SERVER['REMOTE_ADDR']
];

// Output data as a CSV file
$filename = 'server_info.csv';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array('Key', 'Value')); // CSV header

foreach ($server_info as $key => $value) {
    fputcsv($output, array($key, $value));
}

fclose($output);
exit;
