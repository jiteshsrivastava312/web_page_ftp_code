<?php
// File download script
if (isset($_GET['file'])) {
    $file = '/tmp/' . basename($_GET['file']); // Sanitize the input

    // Check if the file exists
    if (file_exists($file)) {
        // Set headers to prompt download
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));

        // Read the file and output it
        readfile($file);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "No file specified.";
}
?>

