<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Puzzle Game</title>
    <style>
        /* Gaya Umum */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('https://source.unsplash.com/800x600/?puzzle,game');
            background-size: cover;
            background-position: center;
        }
        .container {
            max-width: 400px;
            width: 90%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }
        p {
            margin-bottom: 20px;
            color: #666;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            font-weight: bold;
            color: white;
            background-color: #3498db;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #2980b9;
        }
        a {
            display: block;
            margin-top: 15px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <p>Buat akun Anda untuk memulai petualangan puzzle!</p>
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username Anda" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required>
            
            <button type="submit">Register</button>
        </form>
        <a href="{{ route('login') }}">Sudah punya akun? Login di sini</a>
    </div>
</body>
</html>
