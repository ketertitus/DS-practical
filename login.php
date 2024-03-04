<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="sign.css">
</head>

<body>
    <header>
        <h1>Distributed systems Assignment Login Page</h1>
    </header>


    <div class="login-form">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieve hashed password from the database

                // Database connection parameters
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "ds_userdb";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check connection
                if ($conn->connect_error) {
                    echo "connection failed!!!";
                    die("Connection failed: " . $conn->connect_error);
                }

                // User input (username or email)
                $userInput = $_POST["username"];

                // Prepare SQL statement
                $sql = "SELECT password FROM users WHERE username = ? OR email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $userInput, $userInput);

                // Execute the statement
                $stmt->execute();

                // Bind result variables
                $stmt->bind_result($hashedPassword);

                // Fetch the result
                if ($stmt->fetch()) {

                    // Password entered by the user during login
                    $userEnteredPassword = $_POST["password"];
                    $hashedUserEnteredPassword = password_hash($userEnteredPassword, PASSWORD_DEFAULT);
                    echo "$hashedPassword.<br>";
                    echo "$hashedUserEnteredPassword.<br>";
                    // Verify if the user-entered password matches the stored hashed password
                    // password_verify($userEnteredPassword, $hashedPassword))
                    if ($userEnteredPassword == $hashedPassword) {
                        echo "match found";
                        // Start the session
                        session_start();

                        // Store the username in the session
                        $_SESSION["username"] = $userInput;
                        echo $_SESSION["username"];

                        // Redirect to the home page
                        header("Location: home.php");
                        exit();
                    } else {
                        // Redirect back to the login page with an error message
                        // header("Location: login.php?error=1");
                        echo "passwords don't match";
                        exit();
                    }
                } else {
                    // No matching user found
                    echo "User not found";
                }

                // Close statement and connection
                $stmt->close();
                $conn->close();
            }


            ?>
        </form>
        <div>Don't have an account,<a href="signup.php">sign up</a>?</div>
        <?php
        // Source page
        $data = 1;
        ?>
        <div><a href="reset.php?data=<?php echo urlencode($data); ?>">Forgot Password?</a></div>

    </div>
    <footer>
        <p>&copy; distributed systems assignment.</p>
    </footer>
</body>

</html>