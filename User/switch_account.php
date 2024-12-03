<?php
session_start();

// Cek jika user sudah login
if (isset($_SESSION['username'])) {
    // Jika user saat ini sudah login, tampilkan nama user
    $current_user = $_SESSION['username'];
} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit;
}

// Jika form switch account disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus sesi saat ini
    session_unset();
    session_destroy();
    
    // Arahkan user ke halaman login
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Switch Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/slate/bootstrap.min.css" 
    integrity="sha384-8iuq0iaMHpnH2vSyvZMSIqQuUnQA7QM+f6srIdlgBrTSEyd//AWNMyEaSF2yPzNQ" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/home.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <h2 class="card-title">Switch Account</h2>
                <p class="card-text">Saat ini Anda login sebagai <strong><?php echo htmlspecialchars($current_user); ?></strong>.</p>
                <form method="POST">
                    <p>Apakah Anda ingin keluar dan login ke akun lain?</p>
                    <button type="submit" class="btn btn-warning">Switch Account</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
