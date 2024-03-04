<?php
include "mail.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="sign.css">
</head>

<body>
    <header>
        <h1>Distrubuted systems assignment sign up page</h1>
    </header>
    <div class="signup-form">
        <h2>Sign Up</h2>
        <form action="signup.php" method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" id="cpassword" name="cpassword" required>
            </div>
            <button type="submit">Sign Up</button>
            <div> Already have an account,<a href="login.php">login</a>?</div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieve form data
                $name = $_POST['name'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $cpassword = $_POST['cpassword'];

                // You can add your validation and database insertion logic here

                // For demonstration purposes, let's just print the received data
                // echo "Username: $username <br>";
                // echo "Email: $email <br>";
                // echo "Password: $password <br>";
                // Example usage:
                if ($password == $cpassword) {
                    if (is_string($name)) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {


                            // Create connection
                            $conn = new mysqli($servername, $dbusername, $dbpassword, $database);

                            // Check connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Hash the password
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                            // SQL query with placeholders
                            $sql = "INSERT INTO `users`(`Username`, `email`, `password`, `Name`) VALUES (?,?,?,?)";

                            // Prepare and bind the statement
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ssss", $username, $email, $password, $name);

                            // Execute the statement
                            if ($stmt->execute()) {
                                $to_email = $email;
                                $subject = "WELCOME!!";
                                $body = "You have signed up to the assignment website";
                                $from_email = "keterdummy@gmail.com";

                                if (sendEmail($to_email, $subject, $body, $from_email)) {
                                    echo "Email sent successfully.";
                                } else {
                                    echo "Email sending failed.";
                                }
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error;
                            }

                            // Close statement and connection
                            $stmt->close();
                            $conn->close();



                            //if db written seccessfully then:
                            $to_email = $email;
                            $subject = "WELCOME";
                            $body = "
                            <h1>Welcome to Our Website</h1>
                            <p>Dear Recipient,</p>
                            <p>Thank you for joining our website. We are excited to have you on board!</p>
                            <p>Best regards,<br>Your Name</p>";

                            $from_email = "keterdummy@gmail.com";

                            if (sendEmail($to_email, $subject, $body, $from_email)) {
                                echo "Email sent successfully.";
                                header("Location: login.php");
                            } else {
                                echo "Email sending failed.";
                            }
                        } else {
                            echo "Email address is not valid.";
                        }
                    } else {
                        echo "Your full name should not contain numbers and symbols";
                    }
                } else {
                    echo "passwords do not match";
                }
            }
            ?>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Your Company. All rights reserved.</p>
    </footer>

</body>

</html>