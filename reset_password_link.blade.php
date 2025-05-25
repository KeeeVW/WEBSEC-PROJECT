<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
</head>
<body>
    <h1>Hello {{ $user->name }},</h1>
    
    <p>You have requested to reset your password. Please click the link below to reset your password:</p>
    
    <p><a href="{{ $resetLink }}">Reset Password</a></p>
    
    <p>This link will expire in 60 minutes.</p>
    
    <p>If you did not request a password reset, no further action is required.</p>
    
    <p>Thank you,<br>{{ config('app.name') }} Team</p>
</body>
</html> 