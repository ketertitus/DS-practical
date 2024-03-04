<?php
include "mail.php";
?>
<?php

?>
<?php
$resetToken = generateResetToken();
$reset = false;
$rUID = "";
// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Destination page (destination.php)
$receivedData = urldecode($_GET['data']); // Decode the URL parameter
echo $receivedData; // Output: Hello
function sendPasswordResetEmail($email, $resetToken)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $to_email = $email;
        $subject = "Reset Password";
        $body = '
        <p>Hello,</p>
        <p>You have requested to reset your password. Please click on the link below to reset your password:</p>
        <p><a href="http://localhost/ds-practical/reset.php?data=' . $resetToken . '">Reset Password</a></p>
        <p>If you did not request this, please ignore this email.</p>
        <p>Regards,<br>Distributed Assignment</p>
        ';

        $from_email = "keterdummy@gmail.com";

        if (sendEmail($to_email, $subject, $body, $from_email)) {
            return "Email sent successfully.";
        } else {
            return "Email sending failed.";
        }
    } else {
        return "Email address is not valid.";
    }
}

?>
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
        <h2>Reset password</h2>
        <?php

        // Prepare the SQL statement with a parameter placeholder
        $sql = "SELECT `rUID`, `time` FROM `reset` WHERE `token` = ?";
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the statement
        $stmt->bind_param("s", $receivedData);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();


        // Check if the query returned any rows
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Access the values of UID and Username columns
            $rUID  = $row['rUID'];
            $reset = true;
        }
        $stmt->close();
        ?>
        <form action="reset.php" method="post">
            <?php if ($receivedData == 1) : ?>
                <div class="form-group">
                    <label for="username">Enter your email adress</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <button type="submit" name="send">send reset email</button>
            <?php elseif ($reset) : ?>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="cpassword">Confirm New Password</label>
                    <input type="password" id="cpassword" name="cpassword" required>
                </div>
                <button type="submit" name="change">reset password</button>
            <?php else : ?>
                <div class="form-group">Invalid reset link</div>
            <?php endif; ?>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['send'])) {
                    $email = $_POST['email'];

                    // Prepare the SQL statement with a parameter placeholder
                    $sql = "SELECT `UID`, `Username` FROM users WHERE email = ?";
                    $stmt = $conn->prepare($sql);

                    // Bind the parameter to the statement
                    $stmt->bind_param("s", $email);

                    // Execute the query
                    $stmt->execute();

                    // Get the result
                    $result = $stmt->get_result();


                    // Check if the query returned any rows
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        // Access the values of UID and Username columns
                        $rUID  = $row['UID'];
                        $username = $row['Username'];

                        // Values to insert or update

                        $token = $resetToken;
                        $time = date("Y-m-d H:i:s"); // Current date and time

                        // Check if the rUID exists in the reset table
                        $sql_check = "SELECT * FROM `reset` WHERE `rUID` = ?";
                        $stmt_check = $conn->prepare($sql_check);
                        $stmt_check->bind_param("s", $rUID);
                        $stmt_check->execute();
                        $result_check = $stmt_check->get_result();

                        if ($result_check->num_rows > 0) {
                            // rUID exists, update the record with current time and token
                            $sql_update = "UPDATE `reset` SET `token` = ?, `time` = ? WHERE `rUID` = ?";
                            $stmt_update = $conn->prepare($sql_update);
                            $stmt_update->bind_param("sss", $token, $time, $rUID);
                            $stmt_update->execute();

                            echo "Record updated successfully.";
                        } else {
                            // rUID does not exist, insert a new record with current time and token
                            $sql_insert = "INSERT INTO `reset`(`rUID`, `token`, `time`) VALUES (?, ?, ?)";
                            $stmt_insert = $conn->prepare($sql_insert);
                            $stmt_insert->bind_param("sss", $rUID, $token, $time);
                            $stmt_insert->execute();

                            echo "Record inserted successfully.";
                        }
                        echo sendPasswordResetEmail($email, $resetToken);
                        // Close the statements and connection
                        $stmt_check->close();
                        if (isset($stmt_update)) $stmt_update->close();
                        if (isset($stmt_insert)) $stmt_insert->close();
                    } else {
                        echo "You do not have an account.";
                    }

                    // Close statement and connection
                    $stmt->close();
                    $conn->close();
                    header("location: sent.html");
                } elseif (isset($_POST['change'])) {
                    // Sign Up button was clicked
                    $cpassword = $_POST['cpassword'];
                    $password = $_POST['password'];
                    if ($password == $cpassword) {

                        // Hash the password
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                        // SQL query with placeholders
                        $sql = "UPDATE `users` SET `password` = ? WHERE `UID` = ?";

                        // Prepare and bind the statement
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $password, $rUID);
                        // Prepare the SQL statement with a parameter placeholder
                        $sql1 = "SELECT `email`, `Username` FROM users WHERE UID = ?";
                        $stmt1 = $conn->prepare($sql1);

                        // Bind the parameter to the statement
                        $stmt1->bind_param("s", $rUID);

                        // Execute the query
                        $stmt1->execute();

                        // Get the result
                        $result1 = $stmt1->get_result();


                        // Check if the query returned any rows
                        if ($result1->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            // Access the values of UID and Username columns
                            $email  = $row['email'];
                            $username = $row['username'];
                            // Execute the statement
                            if ($stmt->execute()) {
                                $to_email = $email;
                                $subject = "SUCCESS";
                                $body = "
                                    <h1>Password Reset Successful</h1>
                                    <p>Dear Recipient,</p>
                                    <p>Your password has been successfully reset. If you did not request this reset, please contact us immediately.</p>
                                    <p>Best regards,<br>Your Name</p>
                                ";
                                $from_email = "keterdummy@gmail.com";

                                if (sendEmail($to_email, $subject, $body, $from_email)) {
                                    echo "Email sent successfully.";
                                } else {
                                    echo "Email sending failed.";
                                }
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error;
                            }
                        }
                        // Close statement and connection
                        $stmt->close();
                        // header("location:login.php");
                    } else {
                        echo "passwords do not match";
                    }
                    $conn->close();
                } else {
                    // Neither button was clicked
                    echo "No button clicked.";
                    $conn->close();
                }
            }
            ?>


        </form>
    </div>
    <footer>
        <p>&copy; Distributed systems assignment.</p>
    </footer>
</body>

</html>