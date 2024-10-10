<!DOCTYPE html>
<html>
<head>
    <title>Vaccination Scheduled</title>
</head>
<body>
    <h1>Hello {{ $registration->name }},</h1>

    <p>Your COVID vaccination has been scheduled.</p>
    <p><strong>Scheduled Date:</strong> {{ $registration->scheduled_date }}</p>
    <p><strong>Vaccination Center:</strong> {{ $registration->vaccineCenter->name }}</p>
    <p><strong>Address:</strong> {{ $registration->vaccineCenter->address }}</p>

    <p>Please arrive at the vaccination center on the scheduled date with your NID.</p>

    <p>Thank you for registering for the COVID vaccine.</p>
</body>
</html>
