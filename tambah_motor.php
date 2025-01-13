<?php
session_start();
require_once 'config.php';

// Cek apakah user adalah admin atau pegawai
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'pegawai')) {
    header("Location: index.php");
    exit();
}

// Ambil data kategori
$sql = "SELECT * FROM categories";
$categories = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_motor = $_POST['nama_motor'];
    $kategori_id = $_POST['kategori_id'];
    $usia_motor = $_POST['usia_motor'];
    $harga = $_POST['harga'];
    $kelebihan = $_POST['kelebihan'];
    $kekurangan = $_POST['kekurangan'];
    
    // Handle upload gambar
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "uploads/";
        $gambar = time() . '_' . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $gambar;
        
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            // File berhasil diupload
        } else {
            $error = "Gagal mengupload file.";
        }
    }

    if (!isset($error)) {
        $sql = "INSERT INTO motor (nama_motor, kategori_id, usia_motor, harga, kelebihan, kekurangan, gambar) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisdsss", $nama_motor, $kategori_id, $usia_motor, $harga, $kelebihan, $kekurangan, $gambar);
        
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Gagal menambahkan data motor.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Motor - Katalog Motor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Tambah Motor Baru</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama Motor</label>
                <input type="text" class="form-control" name="nama_motor" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select class="form-control" name="kategori_id" required>
                    <?php while($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['nama_kategori']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Usia Motor</label>
                <input type="text" class="form-control" name="usia_motor" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" class="form-control" name="harga" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kelebihan</label>
                <textarea class="form-control" name="kelebihan" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kekurangan</label>
                <textarea class="form-control" name="kekurangan" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" class="form-control" name="gambar">
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 