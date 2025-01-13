<?php
session_start();
require_once 'config.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Ambil data kategori
$sql = "SELECT * FROM categories";
$categories = $conn->query($sql);

// Ambil data motor yang akan diedit
$sql = "SELECT * FROM motor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$motor = $result->fetch_assoc();

if (!$motor) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_motor = $_POST['nama_motor'];
    $kategori_id = $_POST['kategori_id'];
    $usia_motor = $_POST['usia_motor'];
    $harga = $_POST['harga'];
    $kelebihan = $_POST['kelebihan'];
    $kekurangan = $_POST['kekurangan'];
    
    // Handle upload gambar baru
    $gambar = $motor['gambar']; // Gunakan gambar lama sebagai default
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "uploads/";
        $gambar = time() . '_' . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $gambar;
        
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            // Hapus gambar lama jika ada
            if ($motor['gambar'] && file_exists($target_dir . $motor['gambar'])) {
                unlink($target_dir . $motor['gambar']);
            }
        } else {
            $error = "Gagal mengupload file.";
        }
    }

    if (!isset($error)) {
        $sql = "UPDATE motor SET nama_motor=?, kategori_id=?, usia_motor=?, 
                harga=?, kelebihan=?, kekurangan=?, gambar=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisdsssi", $nama_motor, $kategori_id, $usia_motor, 
                         $harga, $kelebihan, $kekurangan, $gambar, $id);
        
        if ($stmt->execute()) {
            header("Location: detail_motor.php?id=" . $id);
            exit();
        } else {
            $error = "Gagal mengupdate data motor.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Motor - <?php echo $motor['nama_motor']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Motor</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama Motor</label>
                <input type="text" class="form-control" name="nama_motor" 
                       value="<?php echo $motor['nama_motor']; ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select class="form-control" name="kategori_id" required>
                    <?php while($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>" 
                                <?php echo ($category['id'] == $motor['kategori_id']) ? 'selected' : ''; ?>>
                            <?php echo $category['nama_kategori']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Usia Motor</label>
                <input type="text" class="form-control" name="usia_motor" 
                       value="<?php echo $motor['usia_motor']; ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" class="form-control" name="harga" 
                       value="<?php echo $motor['harga']; ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kelebihan</label>
                <textarea class="form-control" name="kelebihan" rows="3"><?php echo $motor['kelebihan']; ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kekurangan</label>
                <textarea class="form-control" name="kekurangan" rows="3"><?php echo $motor['kekurangan']; ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Gambar</label>
                <?php if($motor['gambar']): ?>
                    <div class="mb-2">
                        <img src="uploads/<?php echo $motor['gambar']; ?>" alt="Current Image" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" name="gambar">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
            </div>
            
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="detail_motor.php?id=<?php echo $id; ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 