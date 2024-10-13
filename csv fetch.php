<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Capture the date input from the user
if (isset($_GET['report_date'])) {
    $reportDate = $_GET['report_date']; // Get the date from the URL
} else {
    // Use current date if no date is provided
    $reportDate = date('Y-m-d');
}

// List of servers with their SSH connection details
$servers = [
    [
        'host' => '10.66.13.106',
        'username' => 'cogent',
        'password' => 'india@123',
    ],
    [
        'host' => '10.66.13.104',
        'username' => 'cogent',
        'password' => 'india@123',
    ],
];

// Web-accessible directory for CSV
$csvDir = '/var/www/html/reports/tmp/';
$csvFile = $csvDir . "output_{$reportDate}.csv"; // Add date to the file name

// Ensure directory exists
if (!is_dir($csvDir)) {
    mkdir($csvDir, 0755, true); // Create directory if it doesn't exist
}

// SSH Commands to get system information
$commands = [
    'Host_IP' => "hostname -I | awk '{print $1}'", // Get the server's host IP address
    'Total_Space' => "df -BG --total | grep total | awk '{print $2}'", // Get total disk space in GB
    'Used_Space' => "df -BG --total | grep total | awk '{print $3}'", // Get used disk space in GB
    'Available_Space' => "df -BG --total | grep total | awk '{print $4}'", // Get available disk space in GB
    'Uptime' => 'uptime -p', // Get uptime
    'Memory_Usage' => "free -g | awk '/Mem:/ {print $2, $3, $4}'", // Get memory info in GB (Total, Used, Free)
];

// Check if CSV exists to add a header only once
$addHeader = !file_exists($csvFile);

// Open the CSV file for writing (append mode)
$fp = fopen($csvFile, 'a');

// Error handling: Check if file opened successfully
if ($fp === false) {
    die("Error: Unable to open or create the file $csvFile");
}

if ($addHeader) {
    // Add headers (Server IP, Host IP, Available Space, Used Space, Total Space, Uptime, Memory (Total, Used, Free), Date, Time)
    $header = ['Server_IP', 'Host_IP', 'Available_Space', 'Used_Space', 'Total_Space', 'Uptime', 'Memory_Total', 'Memory_Used', 'Memory_Free', 'Date', 'Time'];
    fputcsv($fp, $header);
}

// Loop through each server
foreach ($servers as $server) {
    $host = $server['host'];
    $username = $server['username'];
    $password = $server['password'];

    // Establish SSH connection
    $connection = ssh2_connect($host, 22);
    if (!$connection) {
        echo "Failed to connect to $host.\n";
        continue; // Skip to the next server
    }

    if (ssh2_auth_password($connection, $username, $password)) {
        echo "Connected to $host.\n";

        // Initialize an array to hold the data for this server
        $serverData = ['Server_IP' => $host];

        // Collect data from the server
        foreach ($commands as $key => $command) {
            $stream = ssh2_exec($connection, $command);
            if (!$stream) {
                echo "Failed to execute command: $command on $host.\n";
                $serverData[$key] = 'Error executing command';
                continue; // Skip to the next command
            }

            stream_set_blocking($stream, true);
            $result = stream_get_contents($stream);
            fclose($stream);

            // For memory usage, split the result into Total, Used, and Free
            if ($key == 'Memory_Usage') {
                list($total, $used, $free) = explode(' ', trim($result));
                $serverData['Memory_Total'] = $total;
                $serverData['Memory_Used'] = $used;
                $serverData['Memory_Free'] = $free;
            } else {
                // Add data to the array for other fields
                $serverData[$key] = trim($result);
            }
        }

      // Get current date and time
    $currentDate = date('Y-m-d'); // Capture the current date
    $currentTime = date('H:i:s'); // Capture the current time
    $serverData['Date'] = $currentDate;
    $serverData['Time'] = $currentTime;

// Write the data to CSV file
fputcsv($fp, $serverData);


    } else {
        echo "Failed to authenticate to $host.\n";
    }

    // Close the SSH connection
    ssh2_disconnect($connection);
}

// Close the CSV file
fclose($fp);

echo "Data has been written to CSV file: $csvFile\n";

// Provide a link to download the CSV file
$downloadLink = "/reports/tmp/" . basename($csvFile);
echo "<a href='$downloadLink'>Download CSV File</a>";
?>
