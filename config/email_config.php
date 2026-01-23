<?php
// Email Configuration
// Choose 'smtp' for Gmail SMTP or 'api' for SendGrid/Mailgun

define('EMAIL_METHOD', 'smtp'); // 'smtp' or 'api'

// SMTP Configuration (for Gmail/Others)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587); // 587 or 465
define('SMTP_USERNAME', 'rakibuddinraki2003@gmail.com'); // Your Email
define('SMTP_PASSWORD', 'jeftzyzgtrkrycjy'); // App Password (e.g. for Gmail 2FA)
define('SMTP_FROM_EMAIL', 'rakibuddinraki2003@gmail.com'); // Should match SMTP_USERNAME for Gmail
define('SMTP_FROM_NAME', 'Hotel Booking System');

// API Configuration (for SendGrid) - DEPRECATED/BACKUP
define('EMAIL_API_KEY', 'SG.API_KEY_HERE');
define('API_FROM_EMAIL', 'noreply@yourdomain.com');
define('API_FROM_NAME', 'Hotel Booking System');
?>
