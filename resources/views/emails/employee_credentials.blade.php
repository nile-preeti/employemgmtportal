<!DOCTYPE html>
<html>
<head>
    <title>Employee Credentials</title>
</head>
<body>
    <h3>Hello {{ $name }},</h3>
    <p>Your employee account has been created successfully. Below are your login details:</p>
    <p>Login Link - <a href="https://nileprojects.in/hrmodule/login">Click Here</a></p>
    <p><strong>Employee ID:</strong> {{ $emp_id }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>

    <p>Please login and mark your attendance.</p>

    <p>Thanks,<br>Company Name</p>
</body>
</html>
