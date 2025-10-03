<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
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
            border-bottom: 3px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #dc3545;
            margin: 0;
            font-size: 24px;
        }
        .alert {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            border-radius: 0 5px 5px 0;
        }
        .message-box {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
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
        .spam-warning {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 New Contact Form Submission</h1>
            <p>Received on {{ date('F j, Y \a\t g:i A') }}</p>
        </div>

        @if($is_portfolio_contact && $portfolio_user)
        <div class="alert">
            <h3>🎯 Portfolio Contact Alert</h3>
            @if($is_hire_inquiry)
            <p><strong>Type:</strong> <span style="color: #28a745; font-weight: bold;">HIRING INQUIRY - This person is looking for work opportunities</span></p>
            @else
            <p><strong>Type:</strong> General Portfolio Contact</p>
            @endif
        </div>
        @endif

        <div class="info-grid">
            <div class="info-box">
                <h3>👤 Contact Information</h3>
                <p><strong>Name:</strong> {{ $full_name }}</p>
                <p><strong>Email:</strong> <a href="mailto:{{ $email }}">{{ $email }}</a></p>
                <p><strong>Subject:</strong> {{ $subject }}</p>
            </div>
            
            <div class="info-box">
                <h3>🌐 Technical Details</h3>
                <p><strong>IP Address:</strong> {{ $ip }}</p>
                <p><strong>User Agent:</strong> {{ request()->header('User-Agent') }}</p>
                <p><strong>Timestamp:</strong> {{ date('Y-m-d H:i:s') }}</p>
            </div>
        </div>

        @if($is_portfolio_contact && $portfolio_user)
        <div class="info-box">
            <h3>🎨 Portfolio Details</h3>
            <p><strong>Portfolio Owner:</strong> {{ $portfolio_user->name }}</p>
            <p><strong>Portfolio URL:</strong> <a href="{{ url('/' . ($portfolio_user->portfolio_slug ?: $portfolio_user->username)) }}" target="_blank">{{ url('/' . ($portfolio_user->portfolio_slug ?: $portfolio_user->username)) }}</a></p>
            @if($portfolio_user->profession)
            <p><strong>Profession:</strong> {{ $portfolio_user->profession }}</p>
            @endif
            <p><strong>Portfolio Owner Email:</strong> <a href="mailto:{{ $portfolio_user->email }}">{{ $portfolio_user->email }}</a></p>
        </div>
        @endif

        <div class="message-box">
            <h3>💬 Message Content</h3>
            <p style="white-space: pre-wrap; font-size: 14px;">{{ $_message }}</p>
        </div>

        <div class="spam-warning">
            <h4>🛡️ Spam Protection</h4>
            <p><strong>Message Length:</strong> {{ strlen($_message) }} characters</p>
            <p><strong>Word Count:</strong> {{ str_word_count($_message) }} words</p>
            @if(strlen($_message) < 20)
            <p style="color: #dc3545;"><strong>⚠️ Warning:</strong> Very short message - potential spam</p>
            @endif
            @if(str_word_count($_message) < 5)
            <p style="color: #dc3545;"><strong>⚠️ Warning:</strong> Very few words - potential spam</p>
            @endif
        </div>

        <div class="warning">
            <h4>📋 Action Required</h4>
            <p>Please respond to this inquiry within 24-48 hours for best customer service.</p>
            <p><strong>Reply to:</strong> <a href="mailto:{{ $email }}?subject=Re: {{ $subject }}">{{ $email }}</a></p>
        </div>

        <div class="footer">
            <p>This email was automatically generated by the contact form system.</p>
            <p>© {{ date('Y') }} {{ config('settings.title') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

