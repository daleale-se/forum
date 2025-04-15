<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
</head>
<body>

    <ul>
        <li>{{ $thread->temporalUser->assigned_username ?? 'Anonymous' }}</li>
        <li>{{ $thread->category }}</li>
    </ul>

    <h1>{{ $thread->title }}</h1>
    <p>{{ $thread->body }}</p>

</body>
</html>