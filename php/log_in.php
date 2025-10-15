<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .login-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        label, input, select {
            font-size: 20px;
            width: calc(100% - 20px);
            margin-bottom: 10px;
            display: inline-block;
            box-sizing: border-box;
        }

        input, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
            font-size: 24px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #ff0000;
            font-size: 18px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <label for="role">Login as:</label>
                <select id="role" name="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <input type="submit" value="Login">

            <?php
            if(isset($_GET['error']) && $_GET['error'] == 'invalid') {
                echo '<div class="error-message">Invalid credentials</div>';
            }
            ?>
        </form>
    </div>
</body>
</html>
