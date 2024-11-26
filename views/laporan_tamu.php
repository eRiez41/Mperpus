<?php
include '../koneksi.php';

$filterKeperluan = isset($_GET['keperluan']) ? $_GET['keperluan'] : '';
$filterKelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$filterTanggalMulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$filterTanggalSelesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '';
$searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Query untuk mengambil data buku tamu
$sql = "SELECT buku_tamu.*, anggota.nama AS nama_anggota, anggota.kelas
        FROM buku_tamu 
        JOIN anggota ON buku_tamu.id_anggota = anggota.id
        WHERE 1=1";

if ($filterKeperluan != '') {
    $sql .= " AND buku_tamu.keperluan = '$filterKeperluan'";
}

if ($filterKelas != '') {
    $sql .= " AND anggota.kelas LIKE '$filterKelas%'";
}

if ($filterTanggalMulai != '' && $filterTanggalSelesai != '') {
    $sql .= " AND buku_tamu.tanggal_kunjungan BETWEEN '$filterTanggalMulai' AND '$filterTanggalSelesai'";
}

if ($searchKeyword != '') {
    $sql .= " AND (anggota.nama LIKE '%$searchKeyword%' OR anggota.kelas LIKE '%$searchKeyword%')";
}

$sql .= " ORDER BY buku_tamu.tanggal_kunjungan DESC";

$result = mysqli_query($conn, $sql);
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
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>

    <?php include '../includes/sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <?php include '../includes/navbar.php'; ?>

        <!-- Main Content -->
        <div class="dashboard-header mb-0">
            <h2>Buku Tamu Perpustakaan</h2>
        </div>

        <!-- Tabel Buku Tamu -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="keyword" placeholder="Cari..." value="<?php echo htmlspecialchars($searchKeyword); ?>">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalFilter">Filter</button>
                                    <a href="../fungsi/cetak_buku_tamu.php?<?php echo $_SERVER['QUERY_STRING']; ?>" target="_blank" class="btn btn-success">Cetak</a>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Keperluan</th>
                                        <th>Tanggal Kunjungan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result) > 0) {
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr data-bs-toggle='modal' data-bs-target='#modalDetailBukuTamu' data-id='" . $row['id'] . "'>";
                                            echo "<td>" . $no++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama_anggota']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                                            echo "<td>" . htmlspecialchars(ucfirst($row['keperluan'])) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['tanggal_kunjungan']) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>Tidak ada data buku tamu</td></tr>";
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



<!-- Modal Detail Buku Tamu -->
<div class="modal fade" id="modalDetailBukuTamu" tabindex="-1" aria-labelledby="modalDetailBukuTamuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailBukuTamuLabel">Detail Buku Tamu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Data buku tamu lengkap akan dimuat di sini dengan AJAX -->
                <div id="detailBukuTamuContent" class="card">
                    <div class="card-body">
                        <!-- Content will be loaded here with AJAX -->
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button id="editBukuTamu" class="btn btn-warning">Edit</button>
                    <button id="hapusBukuTamu" class="btn btn-danger">Hapus</button>
                    <button id="cetakBukuTamu" class="btn btn-secondary">Cetak</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Buku Tamu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="filterKeperluan" class="form-label">Keperluan</label>
                        <select class="form-select" id="filterKeperluan" name="keperluan">
                            <option value="">Semua</option>
                            <option value="meminjam" <?php echo ($filterKeperluan == 'meminjam') ? 'selected' : ''; ?>>Meminjam</option>
                            <option value="mengunjungi" <?php echo ($filterKeperluan == 'mengunjungi') ? 'selected' : ''; ?>>Mengunjungi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filterKelas" class="form-label">Kelas</label>
                        <select class="form-select" id="filterKelas" name="kelas">
                            <option value="">Semua</option>
                            <option value="7" <?php echo ($filterKelas == '7') ? 'selected' : ''; ?>>7</option>
                            <option value="8" <?php echo ($filterKelas == '8') ? 'selected' : ''; ?>>8</option>
                            <option value="9" <?php echo ($filterKelas == '9') ? 'selected' : ''; ?>>9</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filterTanggalMulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="filterTanggalMulai" name="tanggal_mulai" value="<?php echo htmlspecialchars($filterTanggalMulai); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="filterTanggalSelesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="filterTanggalSelesai" name="tanggal_selesai" value="<?php echo htmlspecialchars($filterTanggalSelesai); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </form>
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
$(document).ready(function() {
    // Populate class field when selecting an anggota
    $('#id_anggota').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var kelas = selectedOption.data('kelas');
        $('#kelas').val(kelas);
    });

    // Load detail buku tamu in the modal
    $('#modalDetailBukuTamu').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        
        $.ajax({
            url: '../fungsi/get_detail_buku_tamu.php',
            type: 'GET',
            data: { id: id },
            success: function(response) {
                $('#detailBukuTamuContent .card-body').html(response);
            }
        });
    });

    // Handle Edit, Hapus, and Cetak buttons
    $('#editBukuTamu').on('click', function() {
        var id = $('#modalDetailBukuTamu').find('[data-id]').data('id');
        window.location.href = 'edit_buku_tamu.php?id=' + id;
    });

    $('#hapusBukuTamu').on('click', function() {
        var id = $('#modalDetailBukuTamu').find('[data-id]').data('id');
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: '../fungsi/hapus_buku_tamu.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response == 'success') {
                        alert('Data buku tamu berhasil dihapus');
                        window.location.reload(); // Reload halaman setelah berhasil menghapus data
                    } else {
                        alert('Gagal menghapus data buku tamu');
                    }
                }
            });
        }
    });

    $('#cetakBukuTamu').on('click', function() {
        var id = $('#modalDetailBukuTamu').find('[data-id]').data('id');
        window.location.href = '../fungsi/cetak_buku_tamu_detail.php?id=' + id;
    });
});
</script>


</body>
</html>
