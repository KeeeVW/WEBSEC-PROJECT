<!DOCTYPE html>
<html>
<head>
    <title>Temporary Password</title>
</head>
<body>
    <h1>Hello {{ $user->name }},</h1>
    
    <p>You have requested to reset your password. Below is your temporary password:</p>
    
    <p><strong>{{ $tempPassword }}</strong></p>
    
    <p>Please use this temporary password to log in. You will be redirected to change your password after logging in.</p>
    
    <p>Thank you,<br>{{ config('app.name') }} Team</p>
</body>
</html> 