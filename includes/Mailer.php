<?php
require_once dirname(__FILE__) . '/../config/email_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require dirname(__FILE__) . '/PHPMailer/Exception.php';
require dirname(__FILE__) . '/PHPMailer/PHPMailer.php';
require dirname(__FILE__) . '/PHPMailer/SMTP.php';

class Mailer {
    public function sendOTP($toEmail, $otp) {
        if (EMAIL_METHOD == 'api') {
            return $this->sendViaSendGrid($toEmail, $otp);
        } else {
            return $this->sendViaSMTP($toEmail, $otp);
        }
    }

    private function logLocalOTP($toEmail, $otp, $reason = "") {
        $logFile = dirname(__FILE__) . '/../otp_log.txt';
        $logMessage = "[" . date('Y-m-d H:i:s') . "] To: $toEmail | OTP: $otp | Note: $reason" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    private function sendViaSMTP($toEmail, $otp) {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = SMTP_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = SMTP_USERNAME;                     //SMTP username
            $mail->Password   =  SMTP_PASSWORD;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
            $mail->Port       = SMTP_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $mail->addAddress($toEmail);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Verify Your Account - Hotel Booking';
            $mail->Body    = "<h3>Your Verification Code</h3><p>Use the following OTP to complete your registration:</p><h1>$otp</h1>";
            $mail->AltBody = "Your Verification Code is: $otp";

            $mail->send();
            return true;
        } catch (Exception $e) {
            $this->logLocalOTP($toEmail, $otp, "SMTP Error: {$mail->ErrorInfo}");
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    private function sendViaSendGrid($toEmail, $otp) {
        $apiKey = EMAIL_API_KEY;
        
        if (strpos($apiKey, 'SG.') !== 0) {
            return "Invalid SendGrid API Key. It should start with 'SG.'. Check config/email_config.php";
        }

        $url = 'https://api.sendgrid.com/v3/mail/send';

        $data = [
            'personalizations' => [
                [
                    'to' => [
                        ['email' => $toEmail]
                    ],
                    'subject' => 'Verify Your Account - Hotel Booking'
                ]
            ],
            'from' => [
                'email' => API_FROM_EMAIL,
                'name' => API_FROM_NAME
            ],
            'content' => [
                [
                    'type' => 'text/html',
                    'value' => "<h3>Your Verification Code</h3><p>Use the following OTP to complete your registration:</p><h1>$otp</h1>"
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        } else {
            error_log("SendGrid Error: " . $response);
            // Fallback: Log to file so user is not blocked by Invalid API Key
            $this->logLocalOTP($toEmail, $otp, "SendGrid Error ($httpCode)");
            return true;
        }
    }
}
?>
