<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('assets/gallery.css') }}">
</head>
<body>
    <h1>Edit Gallery</h1>
    <form action="{{ route('galleries.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ $gallery->name }}" required>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required>{{ $gallery->description }}</textarea>
        </div>
        <div>
            <label for="image">Image:</label>
            <input type="file" name="image" id="image">
            <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->name }}" width="100">
        </div>
        <button type="submit">Update</button>
    </form>
</body>
</html>