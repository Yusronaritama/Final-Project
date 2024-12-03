<?php
session_start();

// Periksa apakah pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "hotel_db");

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses Tambah/Edit/Hapus Akun
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['add_user'])) {
        // Menangani proses tambah akun
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Amankan password
        $role = $_POST['role'];

        $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $username, $email, $password, $role);
        if ($stmt->execute()) {
            header("Location: adminds.php");
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    if (isset($_POST['edit_user'])) {
        // Menangani proses edit akun
        $id = $_POST['id'];
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE users SET name=?, username=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $username, $email, $role, $id);
        if ($stmt->execute()) {
            header("Location: adminds.php");
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    if (isset($_POST['delete_user'])) {
        // Menangani proses hapus akun
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: adminds.php");
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['add_staff'])) {
            // Tambah Staf
            $name = $_POST['name'];
            $position = $_POST['position'];
    
            $stmt = $conn->prepare("INSERT INTO staff (name, position) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $position);
            if ($stmt->execute()) {
                header("Location: adminds.php");
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    
        if (isset($_POST['delete_staff'])) {
            // Hapus Staf
            $id = $_POST['id'];
    
            $stmt = $conn->prepare("DELETE FROM staff WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                header("Location: adminds.php");
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    
        if (isset($_POST['edit_staff'])) {
            // Edit Staf
            $id = $_POST['id'];
            $name = $_POST['name'];
            $position = $_POST['position'];
    
            $stmt = $conn->prepare("UPDATE staff SET name=?, position=? WHERE id=?");
            $stmt->bind_param("ssi", $name, $position, $id);
            if ($stmt->execute()) {
                header("Location: adminds.php");
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
    
}

// Proses Tambah/Edit/Hapus Kamar
if (isset($_POST['add_room'])) {
    // Menangani proses tambah kamar
    $room_name = $_POST['room_name'];
    $room_price = $_POST['room_price'];

    $stmt = $conn->prepare("INSERT INTO rooms (room_name, room_price) VALUES (?, ?)");
    $stmt->bind_param("sd", $room_name, $room_price);
    if ($stmt->execute()) {
        header("Location: adminds.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}

if (isset($_POST['edit_room'])) {
    // Menangani proses edit kamar
    $id = $_POST['id'];
    $room_name = $_POST['room_name'];
    $room_price = $_POST['room_price'];

    $stmt = $conn->prepare("UPDATE rooms SET room_name=?, room_price=? WHERE id=?");
    $stmt->bind_param("sdi", $room_name, $room_price, $id);
    if ($stmt->execute()) {
        header("Location: adminds.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}

if (isset($_POST['delete_room'])) {
    // Menangani proses hapus kamar
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: adminds.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch Data
$users = $conn->query("SELECT * FROM users");
$rooms = $conn->query("SELECT * FROM rooms");
// Fetch Data Staf
$staff = $conn->query("SELECT * FROM staff");
$staff_count = $staff->num_rows;


// Fetch Total Pemasukan dari Reservasi
$reservations = $conn->query("SELECT SUM(total_price) AS total_income FROM reservations");
$reservation_data = $reservations->fetch_assoc();
$total_income = $reservation_data['total_income'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/slate/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #343a40;
        }
        .container {
            margin-top: 30px;
        }
        .table-container {
            background: #212529;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center text-light">Welcome, Admin</h1>

        <!-- Total Pemasukan -->
        <div class="alert alert-info">
            <h4 class="text-center">Total Pemasukan Hotel: Rp <?php echo number_format($total_income, 0, ',', '.'); ?></h4>
            <div class="alert alert-info">
        <h3>Total Staf: <?php echo $staff_count; ?> orang</h3>
    </div>
        </div>

        <!-- User Management Section -->
        <h3 class="text-light mt-4">Manajemen Akun</h3>
        <div class="table-container">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addUserModal">Tambah Akun</button>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    while ($row = $users->fetch_assoc()) {
                        echo "<tr>
                            <td>{$index}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['role']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editUserModal{$row['id']}'>Edit</button>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' name='delete_user' class='btn btn-danger btn-sm'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                        $index++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Room Management Section -->
        <h3 class="text-light mt-4">Manajemen Kamar</h3>
        <div class="table-container">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addRoomModal">Tambah Kamar</button>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kamar</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    while ($row = $rooms->fetch_assoc()) {
                        echo "<tr>
                            <td>{$index}</td>
                            <td>{$row['room_name']}</td>
                            <td>Rp " . number_format($row['room_price'], 0, ',', '.') . "</td>
                            <td>
                                <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editRoomModal{$row['id']}'>Edit</button>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' name='delete_room' class='btn btn-danger btn-sm'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                        $index++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Staff Management Section -->
        <h3 class="text-light mt-4">Manajemen Staf</h3>
        <div class="table-container">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addStaffModal">Tambah Staf</button>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Posisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            <tbody>
                 <?php
                $index = 1;
                 while ($row = $staff->fetch_assoc()) {
                    echo "<tr>
                        <td>{$index}</td>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['position']}</td>
                        <td>
                            <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editStaffModal{$row['id']}'>Edit</button>
                            <form method='POST' style='display:inline-block;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' name='delete_staff' class='btn btn-danger btn-sm'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                        $index++;
                    }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Staf -->
<div class="modal fade" id="addStaffModal" tabindex="-1" role="dialog" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStaffModalLabel">Tambah Staf</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Posisi</label>
                        <input type="text" name="position" class="form-control" required>
                    </div>
                    <button type="submit" name="add_staff" class="btn btn-primary">Tambah Staf</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Modal Tambah Akun -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah Akun</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" class="form-control">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary">Tambah Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kamar -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoomModalLabel">Tambah Kamar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="room_name">Nama Kamar</label>
                            <input type="text" name="room_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="room_price">Harga</label>
                            <input type="number" name="room_price" class="form-control" required>
                        </div>
                        <button type="submit" name="add_room" class="btn btn-primary">Tambah Kamar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
