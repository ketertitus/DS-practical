# DS-practical
# PHP Authentication App

This is a simple PHP authentication app created by Keter Kiplagat Titus. It allows users to sign up, log in, reset their passwords, and receive email notifications for password resets.

## Features

- User Sign Up: Users can create a new account by providing a username, email, and password.
- User Log In: Registered users can log in using their credentials.
- Password Reset: Users can request a password reset if they forget their password.
- Email Notifications: Users receive email notifications for successful password resets.

## Technologies Used

- PHP: Server-side scripting language used for backend development.
- MySQL: Relational database management system used for storing user data.
- PHPMailer: PHP library for sending emails.
- HTML/CSS: Frontend markup and styling.

## Setup Instructions

1. Clone the repository: `git clone https://github.com/ketertitus/php-authentication-app.git`
2. Import the database: Import the provided SQL file (`database.sql`) into your MySQL database.
3. Configure the database: Update the database connection settings in `config.php` with your MySQL database credentials.
4. Install dependencies: If using PHPMailer, install dependencies via Composer (`composer install`).
5. Start the server: Launch a local server (e.g., XAMPP, WAMP) and navigate to the project directory.

## Usage

1. Sign Up: Register a new account by providing your username, email, and password.
2. Log In: Log in to your account using your registered email and password.
3. Password Reset: If you forget your password, request a password reset by clicking the "Forgot Password?" link on the login page.
4. Email Notification: Check your email for a password reset link and follow the instructions to reset your password.

## Contributors

- Keter Kiplagat Titus: Developer (GitHub: [@ketertitus](https://github.com/ketertitus))

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any inquiries or support, please contact Keter Kiplagat Titus at ktitus@gmail.com.
