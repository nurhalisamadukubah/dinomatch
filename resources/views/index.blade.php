<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;600;700&family=Bubblegum+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/mainMenu.css') }}">
    <title>Game Room</title>
</head>

<body>
    {{-- Error Handling --}}
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
        <button class="close" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    {{-- Main Container --}}
    <div class="container">
        {{-- Header --}}
        <header>
            <div class="logo">
                <!-- <img src="/api/placeholder/70/70" alt="DinoMatch Logo"> -->
                <h1>DINOMATCH</h1>
            </div>
            <nav>
                <ul>
                    @if (Session::has('user'))
                        <li><a href="{{ route('profile.index') }}">Profile</a></li>
                        <li><a href="{{ route('galleries.index', ['id' => session('user')->id]) }}">Dinopedia</a></li>
                        <li><a href="{{ route('tutorial.index') }}">Cara Bermain</a></li>
                        <li><a href="{{ route('about.index') }}">Tentang</a></li>
                        <li><a href="{{ route('logout') }}" id="logout-link" onclick="event.preventDefault(); confirmLogout();">Logout</a></li>
                    @else
                        <li><a href="{{ route('login.profile') }}">Profile</a></li>
                        <li><a href="{{ route('galleries.index', ['id' => 0]) }}">Dinopedia</a></li>
                        <li><a href="{{ route('tutorial.index') }}">Cara Bermain</a></li>
                        <li><a href="{{ route('about.index') }}">Tentang</a></li>
                        <li><a href="{{ route('usual') }}">Login</a></li>
                    @endif
                </ul>
            </nav>
        </header>

        {{-- Main Content --}}
        <main>
            {{-- Hero Section --}}
            <div class="hero">
                <h2>DINOMATCH</h2>
                <p>Susun dan Selesaikan Tantangan Puzzle Dinosaurus!</p>
            </div>

            {{-- Game Buttons --}}
            <div class="game-buttons">
                @if (Session::has('user'))
                    <a href="{{ route('gallery.index') }}">
                        <button class="play-btn">PLAY NOW!</button>
                    </a>
                @else
                    <a href="{{ route('login') }}">
                        <button class="play-btn">PLAY NOW!</button>
                    </a>
                @endif
                {{-- Join Section --}}
                <div class="join-section">
                    <p>atau Bergabung Menggunakan Passcode Teman:</p>
                    @if (Session::has('user'))
                        <form action="{{ route('join.post') }}" method="POST" class="join-form">
                    @else
                        <form action="{{ route('login.join') }}" method="POST" class="join-form">
                    @endif
                        @csrf
                        <input type="text" name="code" class="passcode-input" placeholder="Masukkan Passcode" required>
                        <button type="submit" class="join-btn">GABUNG</button>
                    </form>
                </div>
            </div>

            {{-- Feature Cards --}}
            <div class="features">
                {{-- Repeat for each feature --}}
                <div class="feature-card">
                    <div class="feature-icon"><img src="/api/placeholder/45/45" alt="Icon"></div>
                    <h3 class="feature-title">Fun Puzzles</h3>
                    <p class="feature-text">Piece together amazing dinosaur fossils!</p>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer>
            <p>© 2025 DinoMatch - Uncovering Prehistoric Mysteries!</p>
        </footer>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // function confirmLogout() {
        //     Swal.fire({
        //         title: 'Yakin ingin logout?',
        //         text: "Kamu akan keluar dari sesi saat ini.",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         buttonsStyling: false,
        //         customClass: {
        //             confirmButton: 'btn btn-outline-danger',
        //             cancelButton: 'btn btn-secondary'
        //         },
        //         confirmButtonText: 'Ya, Logout!',
        //         cancelButtonText: 'Batal'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             document.getElementById('logout-form').submit();
        //             Swal.fire({
        //                 title: 'Logged Out!',
        //                 text: 'You have been successfully logged out.',
        //                 icon: 'success',
        //                 timer: 2000,
        //                 showConfirmButton: false
        //             });
        //         }
        //     });
        // }

    function confirmLogout() {
    Swal.fire({
        title: 'Yakin ingin logout?',
        text: "Kamu akan keluar dari sesi saat ini.",
        icon: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-outline-danger',
            cancelButton: 'btn btn-secondary',
            popup: 'custom-swal-popup'
        },
        confirmButtonText: 'Ya, Logout!',
        cancelButtonText: 'Batal',
        focusCancel: true, // Focus on cancel button by default for safety
        allowOutsideClick: false, // Prevent accidental clicks outside
        allowEscapeKey: true, // Allow ESC key to cancel
        reverseButtons: true // Put cancel button on the right (common UX pattern)
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Logging out...',
                text: 'Mohon tunggu sebentar.',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate form submission with error handling
            try {
                const form = document.getElementById('logout-form');
                if (form) {
                    form.submit();
                    
                    // Set a timeout to show success message
                    // (In real app, this would be handled by server response)
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Berhasil Logout!',
                            text: 'Kamu telah berhasil keluar dari sesi.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'custom-swal-popup'
                            }
                        });
                    }, 500);
                } else {
                    throw new Error('Form logout tidak ditemukan');
                }
            } catch (error) {
                console.error('Logout error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat logout. Silakan coba lagi.',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-outline-danger',
                        popup: 'custom-swal-popup'
                    },
                    buttonsStyling: false
                });
            }
        }
    });
}

    </script>
</body>

</html>
