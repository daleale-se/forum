<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
</head>
<body>

    <h1>{{ $thread -> title }}</h1>
    <p>{{ $thread -> body }}</p>
    <span>{{ $thread -> category }}</span>

</body>
</html>