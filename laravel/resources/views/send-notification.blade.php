<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification</title>
</head>
<body>
    <form action="/send-notification" method="POST">
        @csrf
        <button type="submit">Send Notification</button>
    </form>
    <a href="/download-notifications">Download Notifications</a>
</body>
</html>
