<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Ensiklopedia Dinosaurus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            color: #16a34a;
        }
        p {
            color: #666;
            line-height: 1.6;
        }
        .button {
            background: #16a34a;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }
        .button:hover {
            background: #15803d;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 id="title">Tentang Ensiklopedia Dinosaurus</h1>
        <p id="description">
            Ensiklopedia Dinosaurus adalah aplikasi interaktif yang memberikan informasi mendalam tentang berbagai jenis dinosaurus.
            Kami ingin mengajak pengguna untuk belajar sambil bermain dengan teka-teki dan fakta menarik seputar dunia prasejarah.
        </p>
        <button class="button" onclick="changeContent()">Pelajari Lebih Lanjut</button>
    </div>

    <script>
        function changeContent() {
            document.getElementById("title").innerText = "Tujuan Ensiklopedia Dinosaurus";
            document.getElementById("description").innerText = "Kami bertujuan untuk membuat pembelajaran tentang dinosaurus lebih menyenangkan dan interaktif melalui game dan konten edukatif.";
        }
    </script>

</body>
</html>
