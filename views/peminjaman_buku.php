<?php
include '../koneksi.php';

$filterKategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
$searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Query untuk menghitung jumlah peminjaman wajib
$sql_wajib = "SELECT COUNT(*) AS jumlah_wajib FROM peminjaman WHERE kategori = 'wajib'";
$result_wajib = mysqli_query($conn, $sql_wajib);
$row_wajib = mysqli_fetch_assoc($result_wajib);
$jumlah_wajib = $row_wajib['jumlah_wajib'];

// Query untuk menghitung jumlah peminjaman opsional
$sql_opsional = "SELECT COUNT(*) AS jumlah_opsional FROM peminjaman WHERE kategori = 'opsional'";
$result_opsional = mysqli_query($conn, $sql_opsional);
$row_opsional = mysqli_fetch_assoc($result_opsional);
$jumlah_opsional = $row_opsional['jumlah_opsional'];
?>

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
    <link rel="stylesheet" href="../css/peminjaman_buku.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/modal.css">
</head>
<body>

    <?php include '../includes/sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <?php include '../includes/navbar.php'; ?>

        <!-- Main Content -->
        <div class="dashboard-header">
            <h2>Pinjaman Buku</h2>
        </div>
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <a href="pinjaman_wajib.php" class="card-link">
                        <div class="card h-100" style="background-color: #f0f0f0;">
                            <div class="card-body text-center">
                                <i class="fas fa-bookmark fa-2x text-primary mb-2"></i>
                                <h5 class="card-title font-weight-bold mb-0" style="font-size: 1.1rem;">Pinjaman Wajib</h5>
                                <p class="card-text lead mb-0" style="font-size: 0.9rem;">Jumlah Pinjaman: <?php echo $jumlah_wajib; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 mb-4">
                    <a href="pinjaman_opsional.php" class="card-link">
                        <div class="card h-100" style="background-color: #f0f0f0;">
                            <div class="card-body text-center">
                                <i class="fas fa-book fa-2x text-success mb-2"></i>
                                <h5 class="card-title font-weight-bold mb-0" style="font-size: 1.1rem;">Pinjaman Opsional</h5>
                                <p class="card-text lead mb-0" style="font-size: 0.9rem;">Jumlah Pinjaman: <?php echo $jumlah_opsional; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Tabel Peminjaman Terbaru -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Peminjaman Terbaru</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="">
                                
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Peminjam</th>
                                            <th>Judul Buku</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include '../koneksi.php';

                                        $currentDate = date('Y-m-d'); // Mendapatkan tanggal saat ini

                                        $sql = "SELECT peminjaman.*, anggota.nama AS nama_peminjam, GROUP_CONCAT(buku.judul SEPARATOR ', ') AS judul_buku
                                                FROM peminjaman
                                                JOIN anggota ON peminjaman.id = anggota.id
                                                LEFT JOIN peminjaman_detail ON peminjaman.kd_peminjaman = peminjaman_detail.kd_peminjaman
                                                LEFT JOIN buku ON peminjaman_detail.kode_buku = buku.kode_buku
                                                WHERE peminjaman.tanggal_pinjam = '$currentDate'"; // Menambahkan filter tanggal saat ini

                                        

                                        $sql .= " GROUP BY peminjaman.kd_peminjaman
                                                  ORDER BY peminjaman.tanggal_pinjam DESC
                                                  LIMIT 5"; // Membatasi jumlah hasil menjadi 5

                                        $result = mysqli_query($conn, $sql);

                                        if (!$result) {
                                            die("Query error: " . mysqli_error($conn));
                                        }

                                        if (mysqli_num_rows($result) > 0) {
                                            $no = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr data-bs-toggle='modal' data-bs-target='#modalDetailPeminjaman' data-id='" . $row['kd_peminjaman'] . "'>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nama_peminjam']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['judul_buku']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['tanggal_pinjam']) . "</td>";
                                                echo "<td>" . htmlspecialchars(ucfirst($row['kategori'])) . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>Tidak ada peminjaman hari ini</td></tr>";
                                        }

                                        mysqli_close($conn);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Modal Detail Peminjaman -->
<div class="modal fade" id="modalDetailPeminjaman" tabindex="-1" aria-labelledby="modalDetailPeminjamanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailPeminjamanLabel">Detail Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Data peminjaman lengkap akan dimuat di sini dengan AJAX -->
                <div id="detailPeminjamanContent" class="card">
                    <div class="card-body">
                        <!-- Content will be loaded here with AJAX -->
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
$(document).ready(function(){
    $('#modalDetailPeminjaman').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var peminjamanId = button.data('id');
        var modal = $(this);
        $.ajax({
            url: '../fungsi/get_detail_peminjaman.php',
            type: 'GET',
            data: {id: peminjamanId},
            success: function(data) {
                $('#detailPeminjamanContent .card-body').html(data);

                // Menambahkan event listener untuk tombol Edit
                $('#editPeminjaman').on('click', function() {
                    var id = $(this).data('id');
                    // Redirect ke halaman edit dengan parameter id peminjaman
                    window.location.href = 'edit_peminjaman.php?id=' + id;
                });

                // Menambahkan event listener untuk tombol Hapus
                $('#hapusPeminjaman').on('click', function() {
                    var id = $(this).data('id');
                    if (confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')) {
                        $.ajax({
                            url: '../fungsi/delete_peminjaman.php',
                            type: 'POST',
                            data: {id: id},
                            success: function(response) {
                                if (response == 'success') {
                                    alert('Peminjaman berhasil dihapus');
                                    location.reload(); // Refresh halaman setelah penghapusan berhasil
                                } else {
                                    alert('Gagal menghapus peminjaman');
                                }
                            }
                        });
                    }
                });
            }
        });
    });
});
</script>


</body>
</html>

