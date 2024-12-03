<?php
session_start();

// Logout logic
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Redirect to login if not logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']); // Sesuaikan dengan nama sesi user Anda
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel App - Beranda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/slate/bootstrap.min.css" 
    integrity="sha384-8iuq0iaMHpnH2vSyvZMSIqQuUnQA7QM+f6srIdlgBrTSEyd//AWNMyEaSF2yPzNQ" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Hotel App</a>
                
            <!-- Navbar Toggler for Mobile -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#rooms">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#facilities">Facilities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#location">Location</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href = login.php>login</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <span class="nav-link text-success">Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
<header class="hero-section text-white text-center py-5">
    <div class="container position-relative z-2">
        <h1 class="display-4">Selamat Datang di Hotel App</h1>
        <p class="lead">Reservasi kamar dengan mudah, cepat, dan aman.</p>
        <?php if (isLoggedIn()): ?>
            <a href="reservasi.php" class="btn btn-light btn-lg" role="button">Reservasi Sekarang</a>
        <?php endif; ?>
    </div>
    <!-- Carousel -->
    <div id="heroCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-bg" style="background-image: url('image/269.jpg');"></div>
            </div>
            <div class="carousel-item">
                <div class="hero-bg" style="background-image: url('image/268.jpg');"></div>
            </div>
            <div class="carousel-item">
                <div class="hero-bg" style="background-image: url('image/269.jpg');"></div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</header>


    <!-- About Section -->
    <section id="about" class="py-5 bg-dark text-white">
        <div class="container text-center">
            <h2>About Us</h2>
            <p class="mt-3">Hotel App menawarkan pengalaman menginap terbaik dengan layanan eksklusif, lokasi strategis, dan fasilitas premium untuk memastikan kenyamanan Anda.</p>
        </div>
    </section>

    <!-- Rating Section -->
    <section class="py-5">
        <div class="container text-center">
            <h2>Customer Ratings</h2>
            <p class="lead mt-3">⭐️⭐️⭐️⭐️⭐️ - 4.8/5 berdasarkan 500 ulasan pelanggan.</p>
            
            <!-- Komentar Pelanggan -->
            <div class="mt-4">
                <h4>Komentar Pelanggan</h4>
                <div class="card mb-3">
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p>"Pelayanan yang luar biasa, staf sangat ramah dan kamar sangat bersih!"</p>
                            <footer class="blockquote-footer">John Doe <cite title="Source Title">via Hotel App</cite></footer>
                        </blockquote>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p>"Sangat nyaman untuk menginap bersama keluarga, fasilitasnya lengkap dan lokasi strategis."</p>
                            <footer class="blockquote-footer">Maria Smith <cite title="Source Title">via Google Reviews</cite></footer>
                        </blockquote>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p>"Harga sangat terjangkau dengan kualitas pelayanan kelas atas. Akan kembali lagi!"</p>
                            <footer class="blockquote-footer">Ahmad Hasan <cite title="Source Title">via TripAdvisor</cite></footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">© 2024 Hotel App. Semua Hak Dilindungi.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="js/index.js"></script>

</body>
</html>