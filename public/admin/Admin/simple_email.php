<?php
// Simple email solution that works with basic PHP mail() function
// This doesn't require SMTP configuration

function sendSimpleOrderEmail($toEmail, $toName, $orderId) {
    $subject = "Order Confirmation - Order #$orderId";
    
    // Simple HTML email
    $message = "
    <html>
    <head>
        <title>Order Confirmation</title>
    </head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #28a745; color: white; padding: 20px; text-align: center;'>
                <h1>Order Confirmation</h1>
                <p>Thank you for your order!</p>
            </div>
            
            <div style='padding: 20px; background: #f9f9f9;'>
                <p>Dear $toName,</p>
                
                <p>Your order has been successfully placed and is being processed.</p>
                
                <div style='background: white; padding: 15px; margin: 15px 0; border-radius: 5px;'>
                    <h3>Order Details:</h3>
                    <p><strong>Order ID:</strong> #$orderId</p>
                    <p><strong>Order Date:</strong> " . date('F j, Y') . "</p>
                    <p><strong>Order Status:</strong> Pending</p>
                </div>
                
                <p>We will notify you once your order is ready for delivery.</p>
                
                <p>If you have any questions about your order, please contact us.</p>
            </div>
            
            <div style='padding: 20px; text-align: center; background: #eee;'>
                <p>Thank you for choosing our service!</p>
                <p><strong>Fruitables Store Team</strong></p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Fruitables Store <noreply@fruitables.com>" . "\r\n";
    $headers .= "Reply-To: support@fruitables.com" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    // Try to send email
    $mailSent = @mail($toEmail, $subject, $message, $headers);
    
    // Log the attempt
    $logMessage = date('Y-m-d H:i:s') . " - Order #$orderId - Email to $toEmail - " . ($mailSent ? 'SENT (Simple)' : 'FAILED (Simple)') . "\n";
    file_put_contents('email_log.txt', $logMessage, FILE_APPEND);
    
    return $mailSent;
}

// Override the main function to use simple email
function sendOrderConfirmationEmail($toEmail, $toName, $orderId, $orderDetails = []) {
    return sendSimpleOrderEmail($toEmail, $toName, $orderId);
}
?> 