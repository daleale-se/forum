<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
</head>
<body>
    
    <h1>Threads</h1>

    @foreach ($threads as $thread)
        <li>{{ $thread -> title }}</li>
    @endforeach

</body>
</html>