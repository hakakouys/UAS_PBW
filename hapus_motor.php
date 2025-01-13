<?php
session_start();
require_once 'config.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Ambil informasi gambar sebelum menghapus
$sql = "SELECT gambar FROM motor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$motor = $result->fetch_assoc();

// Hapus data motor
$sql = "DELETE FROM motor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Hapus file gambar jika ada
    if ($motor['gambar'] && file_exists("uploads/" . $motor['gambar'])) {
        unlink("uploads/" . $motor['gambar']);
    }
    header("Location: index.php");
} else {
    echo "Gagal menghapus data motor.";
}
exit(); 