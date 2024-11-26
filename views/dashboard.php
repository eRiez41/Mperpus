<!-- views/dashboard.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sederhana</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>

    <?php 
    include '../includes/sidebar.php'; 
    include '../koneksi.php';

    // Mengambil jumlah buku dari tabel buku
    $sqlBuku = "SELECT COUNT(*) AS total_buku FROM buku";
    $resultBuku = mysqli_query($conn, $sqlBuku);
    $rowBuku = mysqli_fetch_assoc($resultBuku);
    $totalBuku = $rowBuku['total_buku'];

    // Mengambil jumlah kategori dari tabel buku
    $sqlKategori = "SELECT COUNT(DISTINCT kategori) AS total_kategori FROM buku";
    $resultKategori = mysqli_query($conn, $sqlKategori);
    $rowKategori = mysqli_fetch_assoc($resultKategori);
    $totalKategori = $rowKategori['total_kategori'];

    // Mengambil jumlah peminjaman dari tabel peminjaman
    $sqlPinjaman = "SELECT COUNT(*) AS total_pinjaman FROM peminjaman";
    $resultPinjaman = mysqli_query($conn, $sqlPinjaman);
    $rowPinjaman = mysqli_fetch_assoc($resultPinjaman);
    $totalPinjaman = $rowPinjaman['total_pinjaman'];

    // Mengambil jumlah anggota dari tabel anggota
    $sqlAnggota = "SELECT COUNT(*) AS total_anggota FROM anggota";
    $resultAnggota = mysqli_query($conn, $sqlAnggota);
    $rowAnggota = mysqli_fetch_assoc($resultAnggota);
    $totalAnggota = $rowAnggota['total_anggota'];

    mysqli_close($conn);
    ?>

    <!-- Content -->
    <div class="content">
        <?php include '../includes/navbar.php'; ?>

        <!-- Main Content -->
        <div class="dashboard-header">
            <h2>Dashboard</h2>
        </div>
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card h-100" style="background-color: #f0f0f0">
                    <div class="card-body text-center">
                        <i class="fas fa-book fa-2x text-primary mb-2"></i>
                        <h5 class="card-title font-weight-bold mb-0" style="font-size: 1.1rem;">Total Buku</h5>
                        <p class="card-text lead mb-0" style="font-size: 0.9rem;">Jumlah Buku: <?php echo $totalBuku; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100" style="background-color: #f0f0f0;">
                    <div class="card-body text-center">
                        <i class="fas fa-tags fa-2x text-success mb-2"></i>
                        <h5 class="card-title font-weight-bold mb-0" style="font-size: 1.1rem;">Total Kategori</h5>
                        <p class="card-text lead mb-0" style="font-size: 0.9rem;">Jumlah Kategori: <?php echo $totalKategori; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100" style="background-color: #f0f0f0;">
                    <div class="card-body text-center">
                        <i class="fas fa-handshake fa-2x text-info mb-2"></i>
                        <h5 class="card-title font-weight-bold mb-0" style="font-size: 1.1rem;">Total Peminjaman</h5>
                        <p class="card-text lead mb-0" style="font-size: 0.9rem;">Jumlah Peminjaman: <?php echo $totalPinjaman; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100" style="background-color: #f0f0f0;">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x text-warning mb-2"></i>
                        <h5 class="card-title font-weight-bold mb-0" style="font-size: 1.1rem;">Total Anggota</h5>
                        <p class="card-text lead mb-0" style="font-size: 0.9rem;">Jumlah Anggota: <?php echo $totalAnggota; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src="../script/sidebar.js"></script>

</body>
</html>

