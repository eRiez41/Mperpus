<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota</title>
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

    <!-- Content -->
    <div class="content">
        <?php include '../includes/navbar.php'; ?>

        <!-- Main Content -->
        <div class="dashboard-header">
            <h2>Tambah Anggota</h2>
        </div>
          
        <div class="container-fluid mt-4">
            <form action="../fungsi/fungsi_tambah_anggota.php" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nisn" class="form-label">NISN</label>
                        <input type="text" class="form-control" id="nisn" name="nisn" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <div class="col-md-6">
                        <label for="telepon" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kelas" class="form-label">Kelas</label>
                        <select class="form-select" id="kelas" name="kelas" required>
                            <option value="">Pilih Kelas</option>
                            <optgroup label="Kelas 7">
                                <option value="7A">Kelas 7A</option>
                                <option value="7B">Kelas 7B</option>
                                <option value="7C">Kelas 7C</option>
                                <option value="7D">Kelas 7D</option>
                            </optgroup>
                            <optgroup label="Kelas 8">
                                <option value="8A">Kelas 8A</option>
                                <option value="8B">Kelas 8B</option>
                                <option value="8C">Kelas 8C</option>
                                <option value="8D">Kelas 8D</option>
                            </optgroup>
                            <optgroup label="Kelas 9">
                                <option value="9A">Kelas 9A</option>
                                <option value="9B">Kelas 9B</option>
                                <option value="9C">Kelas 9C</option>
                                <option value="9D">Kelas 9D</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="foto-siswa" class="form-label">Foto Siswa (opsional, max 1MB)</label>
                        <input type="file" class="form-control" id="foto-siswa" name="foto-siswa">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                            <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                            <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" required>
                        </div>
                    </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-warning" onclick="history.back()">Batal</button>
            </form>
        </div>

        <!-- Bootstrap JS and dependencies -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
        <script src="../script/sidebar.js"></script>
    </div>
</body>
</html>

