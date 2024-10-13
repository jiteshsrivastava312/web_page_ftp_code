<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Date</title>
    <style>
        body {
            background-image: url('https://cogenteservices.com/wp-content/uploads/2019/08/banner-logo.png'); /* Replace with your background image URL */
            background-size: cover;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif; /* Optional: Set a preferred font family */
            color: #333; /* Optional: Set text color */
            height: 100vh; /* Make sure the background covers the entire viewport height */
            margin: 0; /* Remove default body margin */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .select-date-container {
            background-color: rgba(255, 255, 255, 0.8); /* Optional: Add a semi-transparent background to the content */
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }
        .select-date-container h2 {
            margin-bottom: 20px;
        }
        .select-date-container label {
            display: block;
            margin-bottom: 10px;
        }
        .select-date-container input[type="date"],
        .select-date-container select,
        .select-date-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .select-date-container input[type="submit"] {
            background-color: #4CAF50; /* Green background */
            color: white;
            border: none;
            cursor: pointer;
        }
        .select-date-container input[type="submit"]:hover {
            background-color: #45a049; /* Darker green background on hover */
        }
    </style>
</head>
<body>
    <div class="select-date-container">
        <h2>Select Date</h2>
        <form action="fetch.php" method="get">
            <label for="selectedDate">Select Date:</label>
            <input type="date" id="selectedDate" name="date" required><br><br>

                <!-- Add more process options as needed -->
            </select><br><br>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>

