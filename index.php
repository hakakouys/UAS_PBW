<?php
session_start();
require_once 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data motor dari database
$sql = "SELECT m.*, c.nama_kategori 
        FROM motor m 
        LEFT JOIN categories c ON m.kategori_id = c.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Motor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Katalog Motor</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'pegawai'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="tambah_motor.php">Tambah Motor</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex">
                    <a href="logout.php" class="btn btn-outline-light">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <?php if($row['gambar']): ?>
                        <img src="uploads/<?php echo $row['gambar']; ?>" class="card-img-top" alt="<?php echo $row['nama_motor']; ?>">
                    <?php else: ?>
                        <img src="placeholder.jpg" class="card-img-top" alt="No Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['nama_motor']; ?></h5>
                        <p class="card-text">
                            <strong>Kategori:</strong> <?php echo $row['nama_kategori']; ?><br>
                            <strong>Usia:</strong> <?php echo $row['usia_motor']; ?><br>
                            <strong>Harga:</strong> Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                        </p>
                        <a href="detail_motor.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Detail</a>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <a href="edit_motor.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="hapus_motor.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus motor ini?')">Hapus</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 