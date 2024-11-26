<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anggota</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/daftar_buku.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/modal.css">
</head>
<body>

<?php include '../includes/sidebar.php'; ?>
<div class="content">
    <?php include '../includes/navbar.php'; ?>
    
    <div class="dashboard-header">
        <h2>Edit Anggota</h2>
    </div>
    
    <div class="container-fluid mt-4">
        <?php
        include '../koneksi.php';

        if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($conn, $_GET['id']);
            $sql = "SELECT * FROM anggota WHERE id = '$id'";
            $result = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                ?>
                <form method="POST" enctype="multipart/form-data" action="../fungsi/update_anggota.php">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    <input type="hidden" name="existing_foto_siswa" value="<?php echo htmlspecialchars($row['foto_siswa']); ?>">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" class="form-control" id="nisn" name="nisn" value="<?php echo htmlspecialchars($row['nisn']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($row['alamat']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" value="<?php echo htmlspecialchars($row['telepon']); ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo htmlspecialchars($row['kelas']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="foto_siswa" class="form-label">Foto Siswa (opsional, max 1MB)</label>
                            <input type="file" class="form-control" id="foto_siswa" name="foto_siswa">
                        </div>
                        <?php if ($row['foto_siswa']): ?>
                            <div class="col-md-6">
                                <img src="../uploadan/<?php echo htmlspecialchars($row['foto_siswa']); ?>" alt="Foto Siswa" class="img-fluid mt-3">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                            <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" value="<?php echo htmlspecialchars($row['tahun_ajaran']); ?>" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-warning" onclick="history.back()">Batal</button>
                </form>
                <?php
            } else {
                echo "<p>Data anggota tidak ditemukan.</p>";
            }

            mysqli_close($conn);
        } else {
            echo "<p>Parameter tidak valid.</p>";
        }
        ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="../script/sidebar.js"></script>
</div>

</body>
</html>
