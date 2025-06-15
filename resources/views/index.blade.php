<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;600;700&family=Bubblegum+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/mainMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/sweetalert.css') }}">
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

        function confirmLogout() {
            Swal.fire({
                title: 'Akhiri Ekspedisi?',
                html: 'Apakah kamu yakin?<br><strong>Kemajuan kamu akan tersimpan dalam Dinopedia!</strong>',
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-outline-danger',
                    cancelButton: 'btn btn-secondary',
                    popup: 'dinomatch-theme'
                },
                confirmButtonText: 'Ya, Berkemas!',
                cancelButtonText: 'Lanjut Menggali',
                focusCancel: true, // Focus on cancel button by default for safety
                allowOutsideClick: false, // Prevent accidental clicks outside
                allowEscapeKey: true, // Allow ESC key to cancel
                reverseButtons: true, // Put cancel button on the right (common UX pattern)
                footer: 'Penemuan dinosaurus kamu selalu aman bersama kami!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state with DinoMatch theme
                    Swal.fire({
                        title: 'Mengamankan Situs...',
                        html: 'Mohon tunggu sebentar, kami sedang menyimpan hasil permainanmu.',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'dinomatch-theme'
                        },
                        footer: 'Menyimpan jejak petualangan ...',
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
                                    title: 'Situs Penggalian Diamankan!',
                                    html: 'Terima kasih telah menjelajah bersama DinoMatch!<br>Sampai jumpa di petualangan prasejarah berikutnya! 🦕',
                                    icon: 'success',
                                    timer: 5000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'dinomatch-theme'
                                    },
                                    footer: 'Tetap gali pengetahuan!'
                                });
                            }, 1000);
                        } else {
                            throw new Error('Form logout tidak ditemukan');
                        }
                    } catch (error) {
                        console.error('Logout error:', error);
                        Swal.fire({
                            title: 'Oops! Fosil Tertinggal!',
                            html: 'Terjadi kesalahan saat mengamankan situs penggalian.<br>Silakan coba lagi, Paleontolog Muda!',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-outline-danger',
                                popup: 'dinomatch-theme'
                            },
                            confirmButtonText: 'Coba Lagi',
                            buttonsStyling: false,
                            footer: 'Setiap ahli paleontologi pernah mengalami kesalahan!'
                        });
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Show cancelled message with DinoMatch theme
                    Swal.fire({
                        title: 'Teruskan Eksplorasi!',
                        html: 'Penggalian kamu berlanjut! Selamat berburu fosil! 🔍',
                        icon: 'info',
                        confirmButtonText: 'Kembali Berpetualang!',
                        customClass: {
                            popup: 'dinomatch-theme'
                        },
                        footer: 'Penemuan terbaik masih menanti!',
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            });
        }

        // Alternative function for different contexts
        function confirmLogoutEnglish() {
            Swal.fire({
                title: 'End Your Dig?',
                html: 'Are you sure you want to leave your excavation site?<br><strong>Your progress will be saved in the fossil record!</strong>',
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-outline-danger',
                    cancelButton: 'btn btn-secondary',
                    popup: 'dinomatch-theme'
                },
                confirmButtonText: 'Yes, Pack Up!',
                cancelButtonText: 'Keep Digging',
                focusCancel: true,
                allowOutsideClick: false,
                allowEscapeKey: true,
                reverseButtons: true,
                footer: 'Your dinosaur discoveries are always safe with us!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Securing Site...',
                        html: 'Please wait while we save your excavation progress.',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'dinomatch-theme'
                        },
                        footer: 'Saving prehistoric adventure traces...',
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const form = document.getElementById('logout-form');
                        if (form) {
                            form.submit();
                            
                            setTimeout(() => {
                                Swal.fire({
                                    title: 'Dig Site Secured!',
                                    html: 'Thanks for exploring with DinoMatch!<br>See you on your next prehistoric adventure! 🦕',
                                    icon: 'success',
                                    timer: 3000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'dinomatch-theme'
                                    },
                                    footer: 'Keep digging for knowledge!'
                                });
                            }, 1000);
                        } else {
                            throw new Error('Logout form not found');
                        }
                    } catch (error) {
                        console.error('Logout error:', error);
                        Swal.fire({
                            title: 'Oops! Fossil Left Behind!',
                            html: 'An error occurred while securing your dig site.<br>Please try again, Junior Paleontologist!',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-outline-danger',
                                popup: 'dinomatch-theme'
                            },
                            confirmButtonText: 'Try Again',
                            buttonsStyling: false,
                            footer: 'Every paleontologist faces challenges!'
                        });
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Keep Exploring!',
                        html: 'Your dig continues! Happy fossil hunting! 🔍',
                        icon: 'info',
                        confirmButtonText: 'Back to Adventure!',
                        customClass: {
                            popup: 'dinomatch-theme'
                        },
                        footer: 'The best discoveries are yet to come!',
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            });
        }

        // Utility function to show DinoMatch themed alerts
        function showDinoMatchAlert(config) {
            const defaultConfig = {
                customClass: {
                    popup: 'dinomatch-theme'
                },
                buttonsStyling: false
            };
            
            return Swal.fire({
                ...defaultConfig,
                ...config
            });
        }

        // Example usage for other alerts in your app
        function showSuccessAlert(title, message) {
            return showDinoMatchAlert({
                title: title,
                html: message,
                icon: 'success',
                confirmButtonText: 'Lanjutkan!',
                timer: 3000,
                timerProgressBar: true
            });
        }

        function showErrorAlert(title, message) {
            return showDinoMatchAlert({
                title: title,
                html: message,
                icon: 'error',
                confirmButtonText: 'Mengerti',
                customClass: {
                    confirmButton: 'btn btn-outline-danger',
                    popup: 'dinomatch-theme'
                }
            });
        }

        function showInfoAlert(title, message) {
            return showDinoMatchAlert({
                title: title,
                html: message,
                icon: 'info',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-outline-primary',
                    popup: 'dinomatch-theme'
                }
            });
        }

        // Export functions for use in other scripts (if using modules)
        // export { confirmLogout, confirmLogoutEnglish, showDinoMatchAlert, showSuccessAlert, showErrorAlert, showInfoAlert };
    </script>
</body>

</html>
