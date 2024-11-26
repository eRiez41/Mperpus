<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/daftar_buku.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>

<?php include '../includes/sidebar.php'; ?>
    <div class="content">
        <?php include '../includes/navbar.php'; ?>
        
        <div class="dashboard-header">
            <h2>Tambah Buku</h2>
        </div>
        
        <div class="container-fluid mt-4">
            <?php
            if (isset($_POST['submit'])) {
                include '../koneksi.php';

                $kode_buku = $_POST['kode_buku'];
                $judul = $_POST['judul'];
                $penerbit = $_POST['penerbit'];
                $tahun_terbit = $_POST['tahun_terbit'];
                $kategori = $_POST['kategori'];
                $isbn = $_POST['isbn'];
                $jumlah_lembar = $_POST['jumlah_lembar'];
                $jumlah_buku = $_POST['jumlah_buku'];
                $tahun_masuk = $_POST['tahun_masuk'];
                $harga_buku = $_POST['harga_buku'];
                $penulis = $_POST['penulis'];
                $tahun_ajaran = $_POST['tahun_ajaran'];
                $kelas = $_POST['kelas'];

                $gambar_buku = null;
                if (isset($_FILES['gambar_buku']) && $_FILES['gambar_buku']['error'] == 0) {
                    $upload_dir = '../uploadan/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    $gambar_buku = basename($_FILES['gambar_buku']['name']);
                    $target_file = $upload_dir . $gambar_buku;

                    if ($_FILES['gambar_buku']['size'] > 1048576) {
                        $image = imagecreatefromstring(file_get_contents($_FILES['gambar_buku']['tmp_name']));
                        imagepng($image, $target_file, 9);
                        imagedestroy($image);
                    } else {
                        move_uploaded_file($_FILES['gambar_buku']['tmp_name'], $target_file);
                    }
                    }
                    $sql = "INSERT INTO buku (kode_buku, judul, penerbit, tahun_terbit, kategori, isbn, jumlah_lembar, jumlah_buku, tahun_masuk, harga_buku, gambar_buku, penulis, tahun_ajaran, kelas)
                    VALUES ('$kode_buku', '$judul', '$penerbit', '$tahun_terbit', '$kategori', '$isbn', '$jumlah_lembar', '$jumlah_buku', '$tahun_masuk', '$harga_buku', '$gambar_buku', '$penulis', '$tahun_ajaran', '$kelas')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>
                    alert('Tambah buku berhasil');
                    window.location.href = 'tambah_buku.php';
                </script>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            mysqli_close($conn);
        }
        ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kode_buku" class="form-label">Kode Buku</label>
                    <input type="text" class="form-control" id="kode_buku" name="kode_buku" required>
                </div>
                <div class="col-md-6">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control" id="judul" name="judul" required>
                </div>
                <div class="col-md-6">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" required>
                </div>
                <div class="col-md-6">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                    <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori" required>
                        <option selected disabled value="">Pilih Kategori</option>
                        <option value="Pelajaran">Pelajaran</option>
                        <option value="Fiksi">Fiksi</option>
                        <option value="Non-Fiksi">Non-Fiksi</option>
                        <option value="Ilmiah">Ilmiah</option>
                        <option value="Sejarah">Sejarah</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="jumlah_lembar" class="form-label">Jumlah Lembar</label>
                    <input type="number" class="form-control" id="jumlah_lembar" name="jumlah_lembar" required>
                </div>
                <div class="col-md-6">
                    <label for="jumlah_buku" class="form-label">Jumlah Buku</label>
                    <input type="number" class="form-control" id="jumlah_buku" name="jumlah_buku" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                    <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" required>
                </div>
                <div class="col-md-6">
                    <label for="harga_buku" class="form-label">Harga Buku</label>
                    <input type="number" class="form-control" id="harga_buku" name="harga_buku" required>
                </div>
                <div class="col-md-6">
                    <label for="gambar_buku" class="form-label">Gambar Buku (opsional, max 1MB)</label>
                    <input type="file" class="form-control" id="gambar_buku" name="gambar_buku">
                </div>
                <div class="col-md-6">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input type="text" class="form-control" id="penulis" name="penulis" required>
                </div>
                <div class="col-md-6">
                    <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                    <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" required>
                </div>
                <div class="col-md-6">
                    <label for="kelas" class="form-label">kelas</label>
                    <select class="form-select" id="kelas" name="kelas" required>
                        <option selected disabled value="">Pilih kelas</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <button type="button" class="btn btn-warning" onclick="history.back()">Batal</button>
        </form>
    </div>
    <br>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src="../script/sidebar.js"></script>
