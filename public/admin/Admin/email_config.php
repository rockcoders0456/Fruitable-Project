<?php
// Email Configuration
// Update these settings with your email credentials

// Gmail SMTP Settings (Recommended for testing)
$email_config = [
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'smtp_username' => 'rockcode123456789@gmail.com', // YOUR GMAIL
    'smtp_password' => 'your-16-char-app-password',   // CHANGE THIS TO YOUR APP PASSWORD
    'from_email' => 'rockcode123456789@gmail.com',   // YOUR GMAIL
    'from_name' => 'Fruitables Store',               // YOUR WEBSITE NAME
    'reply_to' => 'rockcode123456789@gmail.com'      // YOUR GMAIL
];

// Alternative: Use your hosting provider's SMTP
// $email_config = [
//     'smtp_host' => 'mail.yourdomain.com',
//     'smtp_port' => 587,
//     'smtp_username' => 'noreply@yourdomain.com',
//     'smtp_password' => 'your-password',
//     'from_email' => 'noreply@yourdomain.com',
//     'from_name' => 'Your Website Name',
//     'reply_to' => 'support@yourdomain.com'
// ];

// Instructions for Gmail setup:
// 1. Enable 2-factor authentication on your Gmail account
// 2. Generate an App Password: https://myaccount.google.com/apppasswords
// 3. Use the App Password instead of your regular password
// 4. Replace 'your-email@gmail.com' with your actual Gmail address
// 5. Replace 'your-app-password' with the generated App Password
?> 