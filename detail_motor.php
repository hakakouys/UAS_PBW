<?php
session_start();
require_once 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID motor dari URL
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Ambil data motor
$sql = "SELECT m.*, c.nama_kategori 
        FROM motor m 
        LEFT JOIN categories c ON m.kategori_id = c.id 
        WHERE m.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$motor = $result->fetch_assoc();

// Jika motor tidak ditemukan
if (!$motor) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Motor - <?php echo $motor['nama_motor']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <?php if($motor['gambar']): ?>
                    <img src="uploads/<?php echo $motor['gambar']; ?>" class="img-fluid rounded" alt="<?php echo $motor['nama_motor']; ?>">
                <?php else: ?>
                    <img src="placeholder.jpg" class="img-fluid rounded" alt="No Image">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h2><?php echo $motor['nama_motor']; ?></h2>
                <hr>
                <p><strong>Kategori:</strong> <?php echo $motor['nama_kategori']; ?></p>
                <p><strong>Usia Motor:</strong> <?php echo $motor['usia_motor']; ?></p>
                <p><strong>Harga:</strong> Rp <?php echo number_format($motor['harga'], 0, ',', '.'); ?></p>
                
                <div class="mt-4">
                    <h4>Kelebihan:</h4>
                    <p><?php echo nl2br($motor['kelebihan']); ?></p>
                </div>
                
                <div class="mt-4">
                    <h4>Kekurangan:</h4>
                    <p><?php echo nl2br($motor['kekurangan']); ?></p>
                </div>
                
                <div class="mt-4">
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="edit_motor.php?id=<?php echo $motor['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="hapus_motor.php?id=<?php echo $motor['id']; ?>" class="btn btn-danger" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus motor ini?')">Hapus</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 