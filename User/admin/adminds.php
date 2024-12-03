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

// Proses Tambah/Edit/Hapus Data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Proses untuk User
    if (isset($_POST['add_user'])) {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $username, $email, $password, $role);
        $stmt->execute();
        header("Location: adminds.php");
        exit;
    }

    // Proses untuk Kamar
    if (isset($_POST['add_room'])) {
        $id = $_POST['id'];
        $room_name = $_POST['room_name'];
        $room_price = $_POST['room_price'];
        $available_rooms = $_post['available_rooms'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("INSERT INTO rooms (id, room_name, room_price, available_rooms, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssis", $room_number, $type, $price, $status);
        $stmt->execute();
        header("Location: adminds.php");
        exit;
        
        // Proses Edit Room
    if (isset($_POST['edit_room'])) {
        $id = $_POST['id'];
        $room_name = $_POST['room_name'];
        $room_price = $_POST['room_price'];
        $available_rooms = $_POST['available_rooms'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE rooms SET id=?, room_name=?, room_price=?, available_rooms=?, status=? WHERE id=?");
        $stmt->bind_param("ssisi", $id, $room_name, $room_price, $available_rooms, $status, $id);
        $stmt->execute();
        header("Location: adminds.php");
        exit;
    }

    // Proses Delete Room
    if (isset($_POST['delete_room_id'])) {
        $id = $_POST['delete_room_id'];

        $stmt = $conn->prepare("DELETE FROM rooms WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: adminds.php");
        exit;
    }
    }
   
    // Proses untuk Staff
    if (isset($_POST['add_staff'])) {
        $name = $_POST['name'];
        $position = $_POST['position'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $stmt = $conn->prepare("INSERT INTO staff (name, position, email, phone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $position, $email, $phone);
        $stmt->execute();
        header("Location: adminds.php");
        exit;
    }

        // Proses Edit Staff
    if (isset($_POST['edit_staff'])) {
        $id = $_POST['staff_id'];
        $name = $_POST['name'];
        $position = $_POST['position'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $stmt = $conn->prepare("UPDATE staff SET name=?, position=?, email=?, phone=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $position, $email, $phone, $id);
        $stmt->execute();
        header("Location: adminds.php");
        exit;
    }

    // Proses Delete Staff
    if (isset($_POST['delete_staff_id'])) {
        $id = $_POST['delete_staff_id'];

        $stmt = $conn->prepare("DELETE FROM staff WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: adminds.php");
        exit;
    }

}

// Fetch Data
$users = $conn->query("SELECT * FROM users");
$rooms = $conn->query("SELECT * FROM rooms");
$staff = $conn->query("SELECT * FROM staff");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
        }

        .section {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        h3 {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center">Selamat Datang, Admin</h1>

        <!-- User Management Section -->
        <div class="section">
            <h3>Manajemen Akun</h3>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Tambah Akun</button>
            <table class="table table-striped table-hover">
                <thead class="table-dark">
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
                                <button class='btn btn-warning btn-sm'>Edit</button>
                                <button class='btn btn-danger btn-sm'>Delete</button>
                            </td>
                        </tr>";
                        $index++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Room Management Section -->
<div class="section">
    <h3>Manajemen Kamar</h3>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addRoomModal">Tambah Kamar</button>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nomor Kamar</th>
                <th>Tipe</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = 1;
            while ($row = $rooms->fetch_assoc()) {
                echo "<tr>
                    <td>{$index}</td>
                    <td>{$row['id']}</td>
                    <td>{$row['room_name']}</td>
                    <td>{$row['room_price']}</td>
                    <td>{$row['available_rooms']}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editRoomModal{$row['id']}'>Edit</button>
                        <form method='POST' style='display:inline-block;'>
                            <input type='hidden' name='delete_room_id' value='{$row['id']}'>
                            <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                        </form>
                    </td>
                </tr>";

                // Modal Edit Room
                echo "
                <div class='modal fade' id='editRoomModal{$row['id']}' tabindex='-1' aria-labelledby='editRoomModalLabel' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <form method='POST'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='editRoomModalLabel'>Edit Kamar</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    <input type='hidden' name='room_id' value='{$row['id']}'>
                                    <div class='mb-3'>
                                        <label for='room_number' class='form-label'>Nama Kamar</label>
                                        <input type='text' class='form-control' name='room_number' value='{$row['room_name']}' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='type' class='form-label'>Price</label>
                                        <input type='text' class='form-control' name='type' value='{$row['room_price']}' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='price' class='form-label'>Available Rooms</label>
                                        <input type='number' class='form-control' name='price' value='{$row['available_rooms']}' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='status' class='form-label'>Status</label>
                                        <select class='form-control' name='status' required>
                                            <option value='available' " . ($row['status'] === 'available' ? 'selected' : '') . ">Available</option>
                                            <option value='unavailable' " . ($row['status'] === 'unavailable' ? 'selected' : '') . ">Unavailable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Tutup</button>
                                    <button type='submit' name='edit_room' class='btn btn-primary'>Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";
                $index++;
            }
            ?>
        </tbody>
    </table>
</div>

        <!-- Staff Management Section -->
<div class="section">
    <h3>Manajemen Staff</h3>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStaffModal">Tambah Staff</button>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Posisi</th>
                <th>Email</th>
                <th>Telepon</th>
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
                        <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editStaffModal{$row['id']}'>Edit</button>
                        <form method='POST' style='display:inline-block;'>
                            <input type='hidden' name='delete_staff_id' value='{$row['id']}'>
                            <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                        </form>
                    </td>
                </tr>";

                // Modal Edit Staff
                echo "
                <div class='modal fade' id='editStaffModal{$row['id']}' tabindex='-1' aria-labelledby='editStaffModalLabel' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <form method='POST'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='editStaffModalLabel'>Edit Staff</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    <input type='hidden' name='staff_id' value='{$row['id']}'>
                                    <div class='mb-3'>
                                        <label for='name' class='form-label'>Nama</label>
                                        <input type='text' class='form-control' name='name' value='{$row['name']}' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='position' class='form-label'>Posisi</label>
                                        <input type='text' class='form-control' name='position' value='{$row['position']}' required>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Tutup</button>
                                    <button type='submit' name='edit_staff' class='btn btn-primary'>Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";
                $index++;
            }
            ?>
        </tbody>
    </table>
</div>

     <!-- Add User Modal -->
     <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Tambah Akun</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" name="add_user" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

         <!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoomModalLabel">Tambah Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id" class="form-label">id</label>
                        <input type="text" class="form-control" name="room_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="room_name" class="form-label">Nama Kamar</label>
                        <input type="text" class="form-control" name="type" required>
                    </div>
                    <div class="mb-3">
                        <label for="room_price" class="form-label">Harga</label>
                        <input type="number" class="form-control" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" name="status" required>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="add_room" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

         <!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStaffModalLabel">Tambah Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Posisi</label>
                        <input type="text" class="form-control" name="position" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="add_staff" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
