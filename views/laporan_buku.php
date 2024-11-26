<!-- views/laporan_buku.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
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
    <!-- Content -->
    <div class="content">
        <?php include '../includes/navbar.php'; ?>
        
        <!-- Main Content -->
        <div class="dashboard-header">
            <h2>Laporan Buku</h2>
        </div>
        
       <!-- Tabel Data Buku -->
<div class="container-fluid mt-4">
    <div class="mb-6">
        <form method="GET" action="">
            <div class="row align-items-end">
                <!-- Kolom untuk kata kunci -->
                <div class="col-md-3 mb-3">
                    <input type="text" class="form-control" name="keyword" placeholder="Cari Judul Buku" value="<?php echo htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES); ?>">
                </div>
                <!-- Filter untuk kategori -->
                <div class="col-md-2 mb-3">
                    <select class="form-select" name="kategori">
                        <option value="">Semua Kategori</option>
                        <option value="Pelajaran" <?php if (isset($_GET['kategori']) && $_GET['kategori'] == 'Pelajaran') echo 'selected'; ?>>Pelajaran</option>
                        <option value="Fiksi" <?php if (isset($_GET['kategori']) && $_GET['kategori'] == 'Fiksi') echo 'selected'; ?>>Fiksi</option>
                        <option value="Non-Fiksi" <?php if (isset($_GET['kategori']) && $_GET['kategori'] == 'Non-Fiksi') echo 'selected'; ?>>Non-Fiksi</option>
                        <option value="Ilmiah" <?php if (isset($_GET['kategori']) && $_GET['kategori'] == 'Ilmiah') echo 'selected'; ?>>Ilmiah</option>
                        <option value="Sejarah" <?php if (isset($_GET['kategori']) && $_GET['kategori'] == 'Sejarah') echo 'selected'; ?>>Sejarah</option>
                    </select>
                </div>
                <!-- Filter untuk tahun ajaran -->
                <div class="col-md-2 mb-3">
                    <select class="form-select" name="tahun_ajaran">
                        <option value="">Semua Tahun Ajaran</option>
                        <?php
                        include '../koneksi.php';
                        $sql_tahun_ajaran = "SELECT DISTINCT tahun_ajaran FROM buku ORDER BY tahun_ajaran DESC";
                        $result_tahun_ajaran = mysqli_query($conn, $sql_tahun_ajaran);
                        if (mysqli_num_rows($result_tahun_ajaran) > 0) {
                            while ($row_tahun_ajaran = mysqli_fetch_assoc($result_tahun_ajaran)) {
                                $selected = (isset($_GET['tahun_ajaran']) && $_GET['tahun_ajaran'] == $row_tahun_ajaran['tahun_ajaran']) ? 'selected' : '';
                                echo "<option value='" . $row_tahun_ajaran['tahun_ajaran'] . "' $selected>" . $row_tahun_ajaran['tahun_ajaran'] . "</option>";
                            }
                        }
                        mysqli_close($conn);
                        ?>
                    </select>
                </div>
                <!-- Filter untuk kelas -->
                <div class="col-md-2 mb-3">
                    <select class="form-select" name="kelas">
                        <option value="">Semua Kelas</option>
                        <option value="7" <?php if (isset($_GET['kelas']) && $_GET['kelas'] == '7') echo 'selected'; ?>>7</option>
                        <option value="8" <?php if (isset($_GET['kelas']) && $_GET['kelas'] == '8') echo 'selected'; ?>>8</option>
                        <option value="9" <?php if (isset($_GET['kelas']) && $_GET['kelas'] == '9') echo 'selected'; ?>>9</option>
                    </select>
                </div>
                <!-- Tombol submit -->
                <div class="col-md-3 mb-3">
                    <button type="submit" class="btn btn-primary btn-block">Cari & Filter</button>
                    <button type="submit" onclick="window.open('../fungsi/cetak_buku.php?keyword=<?php echo htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES); ?>&kategori=<?php echo htmlspecialchars($_GET['kategori'] ?? '', ENT_QUOTES); ?>&tahun_ajaran=<?php echo htmlspecialchars($_GET['tahun_ajaran'] ?? '', ENT_QUOTES); ?>&kelas=<?php echo htmlspecialchars($_GET['kelas'] ?? '', ENT_QUOTES); ?>', '_blank')" class="btn btn-success">Cetak</button>
                </div>
            </div>
        </form>
    </div>
</div>

            <table class="table table-hover">
                <thead class="table table-secondary">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penerbit</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                include '../koneksi.php';
                $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
                $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
                $tahun_ajaran = isset($_GET['tahun_ajaran']) ? $_GET['tahun_ajaran'] : '';
                $kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';

                // Query SQL dengan filter
                $sql = "SELECT * FROM buku WHERE 1=1";
                if ($keyword != '') {
                    $sql .= " AND judul LIKE '%$keyword%'";
                }
                if ($kategori != '') {
                    $sql .= " AND kategori = '$kategori'";
                }
                if ($tahun_ajaran != '') {
                    $sql .= " AND tahun_ajaran = '$tahun_ajaran'";
                }
                if ($kelas != '') {
                    $sql .= " AND kelas = '$kelas'";
                }
                $sql .= " ORDER BY judul ASC"; // Sesuaikan dengan urutan yang diinginkan
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr data-bs-toggle='modal' data-bs-target='#modalDetailBuku' data-id='" . $row['kode_buku'] . "'>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $row['judul'] . "</td>";
                        echo "<td>" . $row['penerbit'] . "</td>";
                        echo "<td>" . $row['kategori'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada data buku</td></tr>";
                }
                mysqli_close($conn);
                ?>
                </tbody>
            </table>
        </div>
        
        <!-- Modal Detail Buku -->
        <div class="modal fade" id="modalDetailBuku" tabindex="-1" aria-labelledby="modalDetailBukuLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailBukuLabel">Detail Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Data buku lengkap akan dimuat di sini dengan AJAX -->
                        <div id="detailBukuContent" class="card">
                            <div class="card-body">
                                <!-- Content will be loaded here with AJAX -->
                            </div>
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
        $('#modalDetailBuku').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var kodeBuku = button.data('id');
            var modal = $(this);
            $.ajax({
                url: '../fungsi/get_detail_buku.php',
                type: 'GET',
                data: {kode_buku: kodeBuku},
                success: function(data) {
                    $('#detailBukuContent .card-body').html(data);
                }
            });
        });
    });
    </script>
</body>
</html>
