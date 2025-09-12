<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Company Created</title>
</head>
<body>
    <h2>New Company Created</h2>

    <p><strong>Name:</strong> {{ $company->name }}</p>

    <p>You are superadmin so you can login with:</p>


    <ul>
        <li><strong>Company Name:</strong> {{ $company->name }}</li>
        <li><strong>Email:</strong> {{ $company->email }}</li>
        <li><strong>Password:</strong> 123456</li>
    </ul>

    <p>
        👉 <a href="{{ url('/login') }}" target="_blank">Login Here</a>
    </p>

    <p><em>This is an automated message from the system.</em></p>
</body>
</html>