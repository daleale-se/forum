<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
</head>
<body>
    <h2>New thread</h2>
    <form>
        <label for="title">Title</label>
        <input type="text" name="title" id="title">
        <label for="body">Body</label>
        <textarea name="body" name="body" id="body"></textarea>
        <label for="resource">Resources</label>
        <input type="file" name="resource" id="resource">
        <label for="category">Category</label>
        <select name="category" id="category">
            <option value="0" selected>General</option>
        </select>
        <button type="submit">Create thread</button>
    </form>
</body>
</html>