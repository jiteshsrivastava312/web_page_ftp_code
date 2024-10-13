<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Capture the date input from the user
if (isset($_GET['report_date'])) {
    $reportDate = $_GET['report_date']; // Get the date from the URL
} else {
    // Use current date if no date is provided
    $reportDate = date('d-m-Y'); // Format as 'dd-mm-yyyy'
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
    'Memory_Usage' => "free -m | awk '/Mem:/ {print $2, $3, $4}'", // Get memory info in GB (Total, Used, Free)
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
    // Add headers in the desired order: Date, Time, Available_Space, Used_Space, Total_Space, Uptime, Memory_Total, Memory_Used, Memory_Free, Server_IP, Host_IP
    $header = ['Date', 'Time', 'Available_Space', 'Used_Space', 'Total_Space', 'Uptime', 'Memory_Total (GB)', 'Memory_Used (GB)', 'Memory_Free (GB)', 'Server_IP', 'Host_IP'];
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
        $serverData = [];

        // Get current date and time in the required format
        $currentDate = date('d-m-Y'); // Capture the current date in 'dd-mm-yyyy' format
        $currentTime = date('H:i:s'); // Capture the current time
        
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
                $serverData['Memory_Total (MB)'] = $total . 'MB'; // Add 'G' for GB
                $serverData['Memory_Used (MB)'] = $used . 'MB';   // Add 'G' for GB
                $serverData['Memory_Free (MB)'] = $free . 'MB';   // Add 'G' for GB
            } else {
                // Add data to the array for other fields
                $serverData[$key] = trim($result);
            }
        }

        // Arrange the data in the desired column order
        $row = [
            'Date' => $currentDate,
            'Time' => $currentTime,
            'Available_Space' => $serverData['Available_Space'],
            'Used_Space' => $serverData['Used_Space'],
            'Total_Space' => $serverData['Total_Space'],
            'Uptime' => $serverData['Uptime'],
            'Memory_Total (MB)' => $serverData['Memory_Total (MB)'],
            'Memory_Used (MB)' => $serverData['Memory_Used (MB)'],
            'Memory_Free (MB)' => $serverData['Memory_Free (MB)'],
            'Server_IP' => $host,
            'Host_IP' => $serverData['Host_IP'],
        ];

        // Write the data to CSV file in the correct order
        fputcsv($fp, $row);

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

