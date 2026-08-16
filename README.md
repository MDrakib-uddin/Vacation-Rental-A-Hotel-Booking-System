# Vacation Rental - Hotel Booking System

A complete PHP-based hotel and vacation rental booking system for managing hotel listings, room availability, user bookings, admin operations, and payment flow. This project includes a customer-facing website and an admin control panel for managing hotels, rooms, bookings, and admin accounts.

## Features

- Hotel listing and room browsing
- Room details page with booking form
- User registration and login
- OTP-based email verification for account registration
- Booking management for users
- Admin dashboard with counts for hotels, rooms, admins, and bookings
- Hotel and room creation, update, delete, and status management
- Payment flow integration for booking confirmation
- Responsive design using Bootstrap and custom CSS

## Tech Stack

- PHP
- MySQL
- PDO for database access
- JavaScript / jQuery
- Bootstrap
- PHPMailer for OTP email delivery
- HTML / CSS / SCSS

## Project Structure

```text
.
├── admin-panel/
│   ├── admins/
│   ├── bookings-admins/
│   ├── hotels-admins/
│   ├── layout/
│   ├── rooms-admins/
│   ├── index.php
│   └── ...
├── auth/
│   ├── login.php
│   ├── register.php
│   ├── otp_verify.php
│   └── logout.php
├── config/
│   ├── config.php
│   └── email_config.php
├── css/
├── fonts/
├── images/
├── includes/
│   ├── footer.php
│   ├── header.php
│   ├── Mailer.php
│   └── PHPMailer/
├── js/
├── rooms/
│   └── room-single.php
├── users/
│   └── booking.php
├── about.php
├── contact.php
├── index.php
├── payment.php
├── payment_success.php
├── rooms.php
├── services.php
├── README.md
├── LICENSE
└── ...
```

## Requirements

Before running the project, make sure you have the following installed:

- PHP 7.4 or newer
- Apache or Nginx
- MySQL 5.7+ or MariaDB
- Composer (optional, not required for this project)
- A local web server such as XAMPP, WAMP, or Laragon

## Installation

1. Clone the repository:

```bash
git clone https://github.com/MDrakib-uddin/-Vacation-Rental-A-Hotel-Booking-System.git
```

2. Move the project into your local web server directory:

- XAMPP: `C:/xampp/htdocs/`
- WAMP: `C:/wamp64/www/`
- Laragon: `C:/laragon/www/`

3. Create a MySQL database named `hotel-booking`.

4. Update database configuration in `config/config.php` if needed:

```php
if (!defined('DBNAME')) define('DBNAME', 'hotel-booking');
if (!defined('APPURL')) define('APPURL', 'http://localhost/hotel-booking');
if (!defined('ADMINURL')) define('ADMINURL', 'http://localhost/hotel-booking/admin-panel');
```

5. Update the email settings in `config/email_config.php` to match your SMTP provider.

6. Start Apache and MySQL from your local server stack.

7. Open the app in your browser:

```text
http://localhost/hotel-booking
```

## Admin Access

To access the admin panel, go to:

```text
http://localhost/hotel-booking/admin-panel/admins/login-admins.php
```

Then log in with your admin credentials created in the database or use your admin account that was added during setup.

## Default Database Setup

This project expects a MySQL database named `hotel-booking` with tables such as:

- `admins`
- `users`
- `hotels`
- `rooms`
- `bookings`
- `utilities`

If your environment does not already include the schema, create the necessary tables before running the app.

## User Flow

- User registers with username, email, and password
- OTP is sent to the email address for verification
- User logs in and can view hotel and room details
- User books a room for selected dates
- Payment is processed through the booking payment page
- Booking records are visible in the user dashboard and admin panel

## Admin Capabilities

- Add, update, deactivate, and delete hotels
- Add, update, deactivate, and delete rooms
- Review all bookings
- Change booking or room status
- View dashboard statistics for the system

## Screenshots

This project uses a custom responsive UI. You can run the app locally and navigate through the landing page, room catalog, booking page, and admin panel.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.

## Contributing

Contributions are welcome. You can fork the project, create a feature branch, make your changes, and submit a pull request.

## Contact

For questions or support, reach out through the project repository or the contact form in the application.
