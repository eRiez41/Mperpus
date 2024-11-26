<!-- views/edit_buku.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
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
        <h2>Edit Buku</h2>
    </div>
    
    <div class="container-fluid mt-4">
        <?php
        include '../koneksi.php';

        if (isset($_GET['kode_buku'])) {
            $kode_buku = mysqli_real_escape_string($conn, $_GET['kode_buku']);
            $sql = "SELECT * FROM buku WHERE kode_buku = '$kode_buku'";
            $result = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                ?>
                <form method="POST" enctype="multipart/form-data" action="../fungsi/update_buku.php">

                    <input type="hidden" name="kode_buku" value="<?php echo htmlspecialchars($row['kode_buku']); ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="<?php echo htmlspecialchars($row['judul']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="penerbit" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?php echo htmlspecialchars($row['penerbit']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" value="<?php echo htmlspecialchars($row['tahun_terbit']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="" disabled>Pilih Kategori</option>
                                <option value="Pelajaran" <?php if ($row['kategori'] == 'Pelajaran') echo 'selected'; ?>>Pelajaran</option>
                                <option value="Fiksi" <?php if ($row['kategori'] == 'Fiksi') echo 'selected'; ?>>Fiksi</option>
                                <option value="Non-Fiksi" <?php if ($row['kategori'] == 'Non-Fiksi') echo 'selected'; ?>>Non-Fiksi</option>
                                <option value="Ilmiah" <?php if ($row['kategori'] == 'Ilmiah') echo 'selected'; ?>>Ilmiah</option>
                                <option value="Sejarah" <?php if ($row['kategori'] == 'Sejarah') echo 'selected'; ?>>Sejarah</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo htmlspecialchars($row['isbn']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="jumlah_lembar" class="form-label">Jumlah Lembar</label>
                            <input type="number" class="form-control" id="jumlah_lembar" name="jumlah_lembar" value="<?php echo htmlspecialchars($row['jumlah_lembar']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jumlah_buku" class="form-label">Jumlah Buku</label>
                            <input type="number" class="form-control" id="jumlah_buku" name="jumlah_buku" value="<?php echo htmlspecialchars($row['jumlah_buku']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                            <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" value="<?php echo htmlspecialchars($row['tahun_masuk']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="harga_buku" class="form-label">Harga Buku</label>
                            <input type="number" class="form-control" id="harga_buku" name="harga_buku" value="<?php echo htmlspecialchars($row['harga_buku']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="gambar_buku" class="form-label">Gambar Buku (opsional, max 1MB)</label>
                            <input type="file" class="form-control" id="gambar_buku" name="gambar_buku">
                        </div>
                        <?php if ($row['gambar_buku']): ?>
                            <div class="col-md-6 mt-3">
                                <img src="../uploadan/<?php echo htmlspecialchars($row['gambar_buku']); ?>" alt="Gambar Buku" class="img-fluid">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="penulis" class="form-label">Penulis</label>
                            <input type="text" class="form-control" id="penulis" name="penulis" value="<?php echo htmlspecialchars($row['penulis']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                            <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" value="<?php echo htmlspecialchars($row['tahun_ajaran']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select class="form-select" id="kelas" name="kelas" required>
                                <option value="" disabled>Pilih Kelas</option>
                                <option value="7" <?php if ($row['kelas'] == '7') echo 'selected'; ?>>7</option>
                                <option value="8" <?php if ($row['kelas'] == '8') echo 'selected'; ?>>8</option>
                                <option value="9" <?php if ($row['kelas'] == '9') echo 'selected'; ?>>9</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="submit">Update</button>
                    <button type="button" class="btn btn-warning" onclick="history.back()">Batal</button>
                </form>
                <?php
            } else {
                echo "<p>Data buku tidak ditemukan.</p>";
            }

            mysqli_close($conn);
        } else {
            echo "<p>Parameter tidak valid.</p>";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            $gambar_buku = $row['gambar_buku'];
            if (!empty($_FILES["gambar_buku"]["name"])) {
                $target_dir = "../uploadan/";
                $target_file = $target_dir . basename($_FILES["gambar_buku"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $uploadOk = 1;

                $check = getimagesize($_FILES["gambar_buku"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    echo "File bukan gambar.";
                    $uploadOk = 0;
                }

                if ($_FILES["gambar_buku"]["size"] > 1000000) {
                    $source_image = $_FILES["gambar_buku"]["tmp_name"];
                    $compressed_image = $target_dir . 'compressed_' . basename($_FILES["gambar_buku"]["name"]);

                    if ($imageFileType == "jpg" || $imageFileType == "jpeg") {
                        $image = imagecreatefromjpeg($source_image);
                        imagejpeg($image, $compressed_image, 75);
                    } elseif ($imageFileType == "png") {
                        $image = imagecreatefrompng($source_image);
                        imagepng($image, $compressed_image, 6);
                    } elseif ($imageFileType == "gif") {
                        $image = imagecreatefromgif($source_image);
                        imagegif($image, $compressed_image);
                    }
                    imagedestroy($image);

                    $target_file = $compressed_image;
                }

                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    echo "Hanya JPG, JPEG, PNG & GIF yang diizinkan.";
                    $uploadOk = 0;
                }

                if ($uploadOk == 0) {
                    echo "File tidak terupload.";
                } else {
                    if (move_uploaded_file($_FILES["gambar_buku"]["tmp_name"], $target_file)) {
                        $gambar_buku = basename($target_file);
                    } else {
                        echo "Terjadi kesalahan saat mengupload file.";
                    }
                }
            }

            $sql = "UPDATE buku SET judul='$judul', penerbit='$penerbit', tahun_terbit='$tahun_terbit', kategori='$kategori', isbn='$isbn', jumlah_lembar='$jumlah_lembar', jumlah_buku='$jumlah_buku', tahun_masuk='$tahun_masuk', harga_buku='$harga_buku', gambar_buku='$gambar_buku', penulis='$penulis', tahun_ajaran='$tahun_ajaran', kelas='$kelas' WHERE kode_buku='$kode_buku'";

            if (mysqli_query($conn, $sql)) {
                echo "<script>
                    alert('Update buku berhasil');
                    window.location.href = 'daftar_buku.php';
                </script>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            mysqli_close($conn);
        }
        ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="../script/sidebar.js"></script>

</body>
</html>
