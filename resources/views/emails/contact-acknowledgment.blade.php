<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Acknowledgment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .message-details {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 30px;
        }
        .highlight {
            color: #007bff;
            font-weight: bold;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Message Received</h1>
            <p>Thank you for contacting us!</p>
        </div>

        <div class="content">
            <p>Dear <span class="highlight">{{ $full_name }}</span>,</p>
            
            <p>We have successfully received your message and will get back to you as soon as possible.</p>

            <div class="info-box">
                <h3>📋 Message Details</h3>
                <p><strong>Subject:</strong> {{ $subject }}</p>
                <p><strong>Sent on:</strong> {{ date('F j, Y \a\t g:i A') }}</p>
                @if($is_portfolio_contact && $portfolio_user)
                <p><strong>Portfolio Owner:</strong> {{ $portfolio_user->name }}</p>
                @if($is_hire_inquiry)
                <p><strong>Type:</strong> <span style="color: #28a745; font-weight: bold;">Hiring Inquiry</span></p>
                @endif
                @endif
            </div>

            <div class="message-details">
                <h4>Your Message:</h4>
                <p style="white-space: pre-wrap;">{{ $_message }}</p>
            </div>

            <div class="warning">
                <strong>⚠️ Important:</strong> If you need to send additional information or have any questions, please reply to this email or contact us directly. Do not reply to this automated message.
            </div>

            <p>We typically respond within 24-48 hours during business days. For urgent matters, please contact us directly.</p>

            <p>Thank you for your interest and we look forward to connecting with you!</p>
        </div>

        <div class="footer">
            <p>This is an automated acknowledgment email. Please do not reply to this message.</p>
            <p>© {{ date('Y') }} {{ config('settings.title') }}. All rights reserved.</p>
            <p><small>This email was sent to {{ $email }} on {{ date('F j, Y \a\t g:i A') }}</small></p>
        </div>
    </div>
</body>
</html>
