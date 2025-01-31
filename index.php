<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uas-web";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM mata_kuliah";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-3 text-center">Daftar Mata Kuliah Prodi Akuntansi</h2>
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_message']}</div>";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "<div class='alert alert-danger' role='alert'>{$_SESSION['error_message']}</div>";
            unset($_SESSION['error_message']);
        }
        ?>
        <a href="create.php" class="btn btn-success mb-3">Tambah Matkul</a>
        <table class="table table-hover table-striped table-bordered">
            <thead class="table-dark">
                <tr style="text-align: center">
                    <th>ID</th>
                    <th>Kode Matkul</th>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody style="text-align: center">
                <?php
                    $sql = "SELECT * FROM mata_kuliah"; 
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['kode_mata_kuliah']}</td>
                                    <td>{$row['nama_mata_kuliah']}</td>
                                    <td>{$row['sks']}</td>
                                    <td>
                                        <a href='update.php?id={$row['id']}' class='btn btn-warning btn-sm'>Update</a>
                                        <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Tidak ada data.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-danger mb-3">Logout</a>
        </div>
    </div>

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
