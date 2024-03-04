
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
</head>

<body>
    <header>
        <h1>Assignment home page</h1>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="logout.php">Logout</a></li>


                </a></li> <!-- Add logout link -->
            </ul>
        </nav>
    </header>

    <div class="main-content">
        <?php
        session_start();
        if (isset($_SESSION['username'])) {
            // Display user details if logged in
            echo "<h2>Welcome, " . $_SESSION['username'] . "</h2>";
            echo "<div class='profile-image'><img src='profile.png' alt='Profile Image'></div>";
        } else {
            // Display login button if not logged in
            echo "<a href='login.php' class='button'>Login</a>";
        }
        ?>
    </div>

    <footer>
        <p>&copy; Distributed systems assignment</p>
    </footer>
</body>

</html>