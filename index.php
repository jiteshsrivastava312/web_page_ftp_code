<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Maintenance Reports</title>
    <style>
        body {
            background-image: url('https://cogenteservices.com/wp-content/uploads/2019/08/banner-logo.png');
            background-size: cover;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif; /* Optional: Set a preferred font family */
            color: #333; /* Optional: Set text color */
            height: 50vh; /* Make sure the background covers the entire viewport height */
            margin: 0; /* Remove default body margin */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.8); /* Optional: Add a semi-transparent background to the login form */
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50; /* Green background */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-container input[type="submit"]:hover {
            background-color: #45a049; /* Darker green background on hover */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Server Maintenance Reports</h2>
        <p>Please login to continue.</p>

        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>

