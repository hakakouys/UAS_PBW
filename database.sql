-- Membuat database
CREATE DATABASE motor;
USE motor;

-- Tabel users untuk login
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);

-- Tabel categories
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL
);

-- Tabel motor
CREATE TABLE motor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_motor VARCHAR(100) NOT NULL,
    kategori_id INT,
    usia_motor VARCHAR(50),
    harga DECIMAL(15,2),
    kelebihan TEXT,
    kekurangan TEXT,
    gambar VARCHAR(255),
    FOREIGN KEY (kategori_id) REFERENCES categories(id)
);

-- Memasukkan data users
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@gmail.com', '123', 'admin'),
('pegawai', 'pegawai@gmail.com', '123', 'pegawai'),
('pengunjung', 'pengunjung@gmail.com', '123', 'pengunjung'); 

-- Menambahkan data kategori
INSERT INTO categories (nama_kategori) VALUES 
('Listrik'),
('Pertalite'),
('Pertamax'); 