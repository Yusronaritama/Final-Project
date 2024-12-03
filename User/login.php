<?php
session_start();
$conn = new mysqli("localhost", "root", "", "hotel_db");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND role = ?");
    $query->bind_param("sss", $username, $password, $role);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        if ($role == "user") {
            header("Location: landing.php");
        } elseif ($role == "admin") {
            header("Location: admin/adminds.php");
        }
    } else {
        $error = "Username, Password, atau Role salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Hotel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/slate/bootstrap.min.css" 
    integrity="sha384-8iuq0iaMHpnH2vSyvZMSIqQuUnQA7QM+f6srIdlgBrTSEyd//AWNMyEaSF2yPzNQ" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #343a40;
        }
        .form-box {
            background: #212529;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
        }
        .form-box h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-box .btn {
            width: 100%;
            margin-top: 10px;
        }
        .text-muted {
            margin-top: 10px;
            text-align: center;
        }
        .text-muted a {
            color: #17a2b8;
        }
        .text-muted a:hover {
            text-decoration: underline;
        }
        .error {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <!-- Username Field -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <!-- Role Selection -->
            <div class="form-group">
                <label for="role">Login sebagai</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="" disabled selected>Pilih role</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <!-- Submit Button -->
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>
        <p class="text-muted">Belum punya akun? <a href="register.php">Daftar</a></p>
    </div>
</body>
</html>
