<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DinoMatch Explorer</title>
    <link href="https://fonts.googleapis.com/css2?family=Cabin :wght@400;600;700&family=Bubblegum+Sans&display=swap" rel="stylesheet">
    <style>
                * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #e6d2b5;
            background-image: url('/api/placeholder/800/600');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            font-family: 'Cabin', sans-serif;
            position: relative;
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/api/placeholder/500/500') repeat;
            opacity: 0.05;
            pointer-events: none;
            z-index: -1;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }
        
        /* Header styles - Game info bar style */
        header {
            width: calc(100vw - 40px);
            margin-left: calc(-50vw + 50% + 20px);
            padding: 15px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #3a4d39;
            border: 4px solid #daa520;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 5px;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            min-height: 80px;
        }
        
        header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/api/placeholder/200/100') no-repeat center;
            background-size: contain;
            opacity: 0.1;
            mix-blend-mode: overlay;
        }
        
        .header-content {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }
        
        .logo {
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .logo img {
            height: 70px;
            width: 70px;
            margin-right: 15px;
            border: 3px solid #daa520;
            border-radius: 50%;
            padding: 5px;
            background-color: #734f30;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            animation: pulse 3s infinite alternate;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.08); }
        }
        
        .logo h1 {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 38px;
            font-weight: 700;
            color: #daa520;
            text-shadow: 3px 3px 0 #331a00, -1px -1px 0 #331a00, 1px -1px 0 #331a00, -1px 1px 0 #331a00, 1px 1px 0 #331a00;
            letter-spacing: 1px;
            margin: 0;
        }
        
        /* Navigation styles */
        nav {
            position: relative;
            z-index: 2;
        }
        
        .back-btn {
            background-color: #734f30;
            color: #f5f5dc;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 600;
            border: 3px solid #daa520;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-btn::before {
            content: "⬅";
            font-size: 20px;
        }
        
        .back-btn:hover {
            background-color: #8b5a2b;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }
        
        /* Main content styles */
        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
        }
        
        /* Login form container */
        .login-container {
            background-color: rgba(58, 77, 57, 0.95);
            padding: 40px;
            border-radius: 25px;
            border: 5px solid #daa520;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
            max-width: 500px;
            width: 100%;
            position: relative;
            transform: rotate(-1deg);
            overflow: hidden;
        }
        
        .login-container::before {
            content: "";
            position: absolute;
            top: -30px;
            left: -30px;
            width: 80px;
            height: 80px;
            background: url('/api/placeholder/80/80') no-repeat center;
            background-size: contain;
            transform: rotate(-20deg);
            opacity: 0.7;
        }
        
        .login-container::after {
            content: "";
            position: absolute;
            bottom: -30px;
            right: -30px;
            width: 70px;
            height: 70px;
            background: url('/api/placeholder/70/70') no-repeat center;
            background-size: contain;
            transform: rotate(25deg);
            opacity: 0.7;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 35px;
            position: relative;
            z-index: 2;
        }
        
        .login-title {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 42px;
            color: #daa520;
            text-shadow: 3px 3px 0 #331a00;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .login-title::after {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 4px;
            background-color: #daa520;
            border-radius: 10px;
        }
        
        .login-subtitle {
            font-size: 18px;
            color: #f5f5dc;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            font-weight: 600;
        }
        
        /* Form styles */
        .login-form {
            position: relative;
            z-index: 2;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-label {
            display: block;
            font-family: 'Bubblegum Sans', cursive;
            font-size: 20px;
            color: #daa520;
            margin-bottom: 8px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .form-input {
            width: 100%;
            padding: 16px 20px;
            background-color: #e6d2b5;
            border: 3px solid #734f30;
            border-radius: 12px;
            color: #3a4d39;
            font-size: 18px;
            font-weight: 600;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: inset 0 3px 8px rgba(0, 0, 0, 0.2);
            font-family: 'Cabin', sans-serif;
        }
        
        .form-input::placeholder {
            color: rgba(58, 77, 57, 0.6);
            font-weight: 500;
        }
        
        .form-input:focus {
            border-color: #daa520;
            box-shadow: 0 0 20px rgba(218, 165, 32, 0.5), inset 0 3px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }
        
        /* Password toggle */
        .password-group {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #734f30;
            font-size: 18px;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #daa520;
        }
        
        /* Remember me checkbox */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }
        
        .checkbox-input {
            width: 20px;
            height: 20px;
            accent-color: #daa520;
            cursor: pointer;
        }
        
        .checkbox-label {
            color: #f5f5dc;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        /* Login button */
        .login-btn {
            width: 100%;
            padding: 18px;
            font-family: 'Bubblegum Sans', cursive;
            font-size: 24px;
            font-weight: 700;
            background-color: #daa520;
            border: 4px solid #734f30;
            color: #331a00;
            border-radius: 15px;
            cursor: pointer;
            box-shadow: 0 6px 0 #b8941c, 0 10px 20px rgba(0, 0, 0, 0.4);
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            overflow: hidden;
            margin-bottom: 25px;
        }
        
        .login-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: 0.5s;
        }
        
        .login-btn:hover::before {
            left: 100%;
        }
        
        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 9px 0 #b8941c, 0 14px 25px rgba(0, 0, 0, 0.4);
        }
        
        .login-btn:active {
            transform: translateY(3px);
            box-shadow: 0 3px 0 #b8941c, 0 6px 10px rgba(0, 0, 0, 0.4);
        }
        
        /* Additional options */
        .login-options {
            text-align: center;
            position: relative;
            z-index: 2;
        }
        
        .forgot-password {
            color: #daa520;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .forgot-password:hover {
            color: #fff;
            text-shadow: 0 0 10px rgba(218, 165, 32, 0.5);
            transform: translateY(-2px);
        }
        
        .signup-link {
            background-color: #734f30;
            padding: 15px 30px;
            border-radius: 12px;
            border: 3px solid #daa520;
            display: inline-block;
            text-decoration: none;
            color: #f5f5dc;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        
        .signup-link:hover {
            background-color: #8b5a2b;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }
        
        /* Decorative elements */
        .decoration {
            position: absolute;
            z-index: 1;
            pointer-events: none;
        }
        
        .fossil-left {
            top: 15%;
            left: 5%;
            width: 120px;
            height: 120px;
            transform: rotate(-20deg);
            opacity: 0.6;
            animation: float 6s ease-in-out infinite;
        }
        
        .fossil-right {
            bottom: 20%;
            right: 8%;
            width: 150px;
            height: 150px;
            transform: rotate(15deg);
            opacity: 0.6;
            animation: float 8s ease-in-out infinite reverse;
        }
        
        .dino-footprint {
            position: absolute;
            width: 30px;
            height: 40px;
            background: url('asset/footprint.png') no-repeat center;
            background-size: contain;
            opacity: 0.2;
            z-index: 1;
        }
        
        .footprint1 { top: 10%; left: 15%; transform: rotate(25deg); }
        .footprint2 { top: 70%; left: 10%; transform: rotate(-15deg); }
        .footprint3 { top: 25%; right: 20%; transform: rotate(35deg); }
        .footprint4 { bottom: 15%; right: 15%; transform: rotate(-25deg); }
        
        @keyframes float {
            0% { transform: translateY(0) rotate(-20deg); }
            50% { transform: translateY(-10px) rotate(-15deg); }
            100% { transform: translateY(0) rotate(-20deg); }
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            header {
                width: calc(100vw - 20px);
                margin-left: calc(-50vw + 50% + 10px);
                margin-top: 10px;
                min-height: 70px;
            }
            
            .header-content {
                flex-direction: column;
                gap: 15px;
                padding: 15px 20px;
            }
            
            .logo h1 {
                font-size: 32px;
            }
            
            .login-container {
                padding: 30px 25px;
                margin: 0 10px;
            }
            
            .login-title {
                font-size: 36px;
            }
            
            .login-subtitle {
                font-size: 16px;
            }
            
            .form-input {
                padding: 14px 18px;
                font-size: 16px;
            }
            
            .login-btn {
                padding: 16px;
                font-size: 22px;
            }
            
            .fossil-left, .fossil-right {
                width: 100px;
                height: 100px;
                opacity: 0.4;
            }
        }
        
        @media (max-width: 480px) {
            header {
                width: calc(100vw - 16px);
                margin-left: calc(-50vw + 50% + 8px);
                margin-top: 8px;
                min-height: 60px;
            }
            
            .logo img {
                height: 50px;
                width: 50px;
            }
            
            .logo h1 {
                font-size: 28px;
            }
            
            .login-container {
                padding: 25px 20px;
            }
            
            .login-title {
                font-size: 32px;
            }
            
            .login-subtitle {
                font-size: 15px;
            }
            
            .form-input {
                padding: 12px 16px;
                font-size: 15px;
            }
            
            .login-btn {
                padding: 14px;
                font-size: 20px;
            }
            
            .fossil-left, .fossil-right {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <div class="header-content">
                <div class="logo">
                    {{-- <img src="/api/placeholder/70/70" alt="DinoMatch Logo"> --}}
                    <h1>DinoMatch</h1>
                </div>
                <nav>
                    <a href="{{ route('room.index') }}" class="back-btn">Kembali</a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <!-- Decorative Elements -->
            <div class="dino-footprint footprint1"></div>
            <div class="dino-footprint footprint2"></div>
            <div class="dino-footprint footprint3"></div>
            <div class="dino-footprint footprint4"></div>
            <img src="asset/skull.png" alt="Fossil decoration" class="decoration fossil-left">
            <img src="asset/dinosaur-skeleton.png" alt="Fossil decoration" class="decoration fossil-right">

            <!-- Login Container -->
            <div class="login-container">
                <div class="login-header">
                    <h2 class="login-title">LOGIN</h2>
                </div>

                <!-- Form with PHP Logic -->
                @if (request()->is('login'))
                    <form class="login-form" id="loginForm" method="POST" action="{{ route('login.post') }}">
                @elseif (request()->is('login/profile'))
                    <form class="login-form" id="loginForm" method="POST" action="{{ route('profile.post') }}">
                @elseif (request()->is('login/join'))
                    <form class="login-form" id="loginForm" method="POST" action="{{ route('join.post') }}">
                        <input type="hidden" value="{{ $code }}" name="code">
                @elseif (request()->is('login/usual'))
                    <form class="login-form" id="loginForm" method="POST" action="{{ route('login.usual') }}">
                @endif
                    @csrf

                    <!-- Username Field -->
                    <div class="form-group">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan Username" required>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password:</label>
                        <div class="password-group">
                            <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan Password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">👁</button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="login-btn">Mulai Bermain!</button>
                </form>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.password-toggle');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁';
            }
        }

        // Optional: Add input focus animation
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>