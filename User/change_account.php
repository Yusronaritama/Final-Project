<?php
session_start();
$conn = new mysqli("localhost", "root", "", "hotel_db");

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil data pengguna saat ini
$current_user = $_SESSION['username'];

// Proses perubahan data akun
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['new_username'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $query = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE username = ?");
    $query->bind_param("sss", $new_username, $new_password, $current_user);

    if ($query->execute()) {
        $_SESSION['username'] = $new_username; // Update sesi dengan username baru
        $success_message = "Akun berhasil diperbarui!";
    } else {
        $error_message = "Terjadi kesalahan. Silakan coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/slate/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Ubah Akun</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        <form method="POST">
            <!-- Username Baru -->
            <div class="form-group">
                <label for="new_username">Username Baru</label>
                <input type="text" id="new_username" name="new_username" class="form-control" placeholder="Masukkan username baru" required>
            </div>
            <!-- Password Baru -->
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Masukkan password baru" required>
            </div>
            <!-- Submit -->
            <button type="submit" class="btn btn-primary btn-block">Perbarui Akun</button>
        </form>
        <a href="index.php" class="btn btn-secondary btn-block mt-3">Kembali ke Beranda</a>
    </div>
</body>
</html>
