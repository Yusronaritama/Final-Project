<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "hotel_db");

// Ambil daftar kamar
$rooms = $conn->query("SELECT * FROM rooms");

// Cek apakah pengguna ingin mengisi formulir reservasi
$isReserveForm = isset($_GET['room_id']);
if ($isReserveForm) {
    $room_id = intval($_GET['room_id']);
    $room = $conn->query("SELECT * FROM rooms WHERE id = $room_id")->fetch_assoc();

    if (!$room) {
        header("Location: landing.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Hotel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/slate/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Hotel App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                
                <!-- Dropdown Settings -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Settings
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="settingsDropdown">
                        <a class="dropdown-item" href="change_account.php">Change Account</a>
                        <a class="dropdown-item" href="switch_account.php">Switch Account</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="landing.php">Beranda</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <?php if ($isReserveForm): ?>
            <!-- Formulir Reservasi -->
            <div class="reservasi-box">
                <h2 class="text-center">Formulir Reservasi</h2>
                <form action="process_reservation.php" method="POST">
                    <input type="hidden" name="room_id" value="<?= htmlspecialchars($room['id']); ?>">
                    <div class="form-group">
                        <label for="room_name">Tipe Kamar</label>
                        <input type="text" id="room_name" class="form-control" value="<?= htmlspecialchars($room['room_name']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="room_price">Harga Per Malam</label>
                        <input type="text" id="room_price" class="form-control" value="Rp<?= number_format($room['room_price'], 0, ',', '.'); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="reservation_date">Tanggal Reservasi</label>
                        <input type="date" id="reservation_date" name="reservation_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="duration">Jangka Waktu Penyewaan (malam)</label>
                        <input type="number" id="duration" name="duration" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method" class="form-control" required>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="cash">Tunai</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Konfirmasi Reservasi</button>
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- Tabel Kamar -->
            <div class="reservasi-box">
                <h2 class="text-center">Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?></h2>
                <h3 class="text-center mb-4">Reservasi Kamar</h3>
                <div class="table-responsive">
                    <table class="table table-dark table-striped text-center">
                        <thead>
                            <tr>
                                <th>Tipe Kamar</th>
                                <th>Harga</th>
                                <th>Sisa Kamar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $rooms->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['room_name']); ?></td>
                                <td>Rp<?= number_format($row['room_price'], 0, ',', '.'); ?></td>
                                <td><?= $row['available_rooms']; ?></td>
                                <td>
                                    <?php if ($row['available_rooms'] > 0): ?>
                                        <a href="?room_id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">Reservasi</a>
                                    <?php else: ?>
                                        <span class="text-danger">Habis</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>