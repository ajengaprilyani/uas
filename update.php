<?php
session_start();
if (!isset($_SESSION['login'])) {
    if ($_SESSION['login'] != true) {
        header("Location: login.php");
        exit;
    }
}

$mysqli = new mysqli('localhost', 'root', '', 'uas-web');

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}


if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $stmt = $mysqli->prepare("SELECT * FROM mata_kuliah WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $mata_kuliah = $result->fetch_assoc();

    // Jika data tidak ditemukan
    if (!$mata_kuliah) {
        $_SESSION['error_message'] = 'Data tidak ditemukan!';
        header('Location: index.php');
        exit();
    }
    $stmt->close();
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $kode_mata_kuliah = $_POST['kode_mata_kuliah'];
    $nama_mata_kuliah = $_POST['nama_mata_kuliah'];
    $sks = intval($_POST['sks']); 

    if (empty($kode_mata_kuliah) || empty($nama_mata_kuliah) || empty($sks)) {
        $_SESSION['error_message'] = 'Semua field harus diisi!';
    } else {
        // Query untuk update data
        $stmt = $mysqli->prepare("UPDATE mata_kuliah SET kode_mata_kuliah = ?, nama_mata_kuliah = ?, sks = ? WHERE id = ?");
        $stmt->bind_param('ssii', $kode_mata_kuliah, $nama_mata_kuliah, $sks, $id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Data berhasil diperbarui!';
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error_message'] = 'Data tidak bisa diperbarui: ' . $stmt->error;
        }
        $stmt->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Mata Kuliah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff758c, #ff7eb3);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
        }
        .form-group input:focus {
            border-color: #007BFF;
            background-color: #fff;
            outline: none;
        }
        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #ff5f75;
            box-shadow: 0px 4px 8px rgb(13, 137, 199);
        }
        .message {
            text-align: center;
            margin-top: 10px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Update Mata Kuliah</h2>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message error">
            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($mata_kuliah['id']); ?>">
        
        <div class="form-group">
            <label for="kode_mata_kuliah">Kode Mata Kuliah</label>
            <input type="text" id="kode_mata_kuliah" name="kode_mata_kuliah" value="<?= htmlspecialchars($mata_kuliah['kode_mata_kuliah']); ?>" required>
        </div>

        <div class="form-group">
            <label for="nama_mata_kuliah">Nama Mata Kuliah</label>
            <input type="text" id="nama_mata_kuliah" name="nama_mata_kuliah" value="<?= htmlspecialchars($mata_kuliah['nama_mata_kuliah']); ?>" required>
        </div>

        <div class="form-group">
            <label for="sks">SKS</label>
            <input type="number" id="sks" name="sks" value="<?= htmlspecialchars($mata_kuliah['sks']); ?>" required>
        </div>

        <div class="form-group">
            <button type="submit">Update</button>
        </div>
    </form>
</div>

</body>
</html>