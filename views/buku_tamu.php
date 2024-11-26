<?php
include '../koneksi.php';

// Handle AJAX requests
if (isset($_GET['get_anggota_by_kelas'])) {
    if (isset($_GET['kelas'])) {
        $kelas = $_GET['kelas'];
        $sql = "SELECT id, nama, tahun_ajaran FROM anggota WHERE kelas = '$kelas'";
        $result = mysqli_query($conn, $sql);
        $options = "<option value='' selected disabled>Pilih Anggota</option>";

        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='" . $row['id'] . "' data-kelas='" . htmlspecialchars($kelas) . "' data-tahun='" . htmlspecialchars($row['tahun_ajaran']) . "'>" . htmlspecialchars($row['nama']) . "</option>";
        }

        echo $options;
        exit;
    }
}

if (isset($_GET['get_tahun_ajaran_by_kelas'])) {
    if (isset($_GET['kelas'])) {
        $kelas = $_GET['kelas'];
        $sql = "SELECT DISTINCT tahun_ajaran FROM anggota WHERE kelas = '$kelas'";
        $result = mysqli_query($conn, $sql);
        $options = "<option value='' selected disabled>Pilih Tahun Ajaran</option>";

        if (!$result) {
            echo mysqli_error($conn); // Tampilkan pesan error jika query gagal
            exit;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='" . htmlspecialchars($row['tahun_ajaran']) . "'>" . htmlspecialchars($row['tahun_ajaran']) . "</option>";
        }

        echo $options;
        exit;
    }
}

if (isset($_GET['get_tahun_ajaran'])) {
    $sql = "SELECT DISTINCT tahun_ajaran FROM anggota";
    $result = mysqli_query($conn, $sql);
    $options = "<option value='' selected disabled>Pilih Tahun Ajaran</option>";

    if (!$result) {
        echo mysqli_error($conn); // Tampilkan pesan error jika query gagal
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $options .= "<option value='" . htmlspecialchars($row['tahun_ajaran']) . "'>" . htmlspecialchars($row['tahun_ajaran']) . "</option>";
    }

    echo $options;
    exit;
}

if (isset($_GET['get_kelas'])) {
    $sql = "SELECT DISTINCT kelas FROM anggota";
    $result = mysqli_query($conn, $sql);
    $options = "<option value='' selected disabled>Pilih Kelas</option>";

    if (!$result) {
        echo mysqli_error($conn); // Tampilkan pesan error jika query gagal
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $options .= "<option value='" . htmlspecialchars($row['kelas']) . "'>" . htmlspecialchars($row['kelas']) . "</option>";
    }

    echo $options;
    exit;
}

// Handle form submission for adding guestbook entry
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_anggota']) && isset($_POST['keperluan']) && isset($_POST['tahun_ajaran'])) {
    $id_anggota = $_POST['id_anggota'];
    $keperluan = $_POST['keperluan'];
    $tahun_ajaran = $_POST['tahun_ajaran'];
    $tanggal_kunjungan = date('Y-m-d H:i:s');

    $sql = "INSERT INTO buku_tamu (id_anggota, keperluan, tahun_ajaran, tanggal_kunjungan) 
            VALUES ('$id_anggota', '$keperluan', '$tahun_ajaran', '$tanggal_kunjungan')";

    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'error';
    }
    exit;
}

// Query untuk mengambil data buku tamu
$sql = "SELECT buku_tamu.*, anggota.nama AS nama_anggota, anggota.kelas
        FROM buku_tamu 
        JOIN anggota ON buku_tamu.id_anggota = anggota.id
        WHERE 1=1";

$searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

if ($searchKeyword != '') {
    $sql .= " AND (anggota.nama LIKE '%" . mysqli_real_escape_string($conn, $searchKeyword) . "%' OR anggota.kelas LIKE '%" . mysqli_real_escape_string($conn, $searchKeyword) . "%')";
}

// Filter untuk Tahun Ajaran
if (isset($_GET['filter_tahun_ajaran']) && $_GET['filter_tahun_ajaran'] != '') {
    $tahun_ajaran_filter = $_GET['filter_tahun_ajaran'];
    if ($tahun_ajaran_filter != 'Semua') {
        $sql .= " AND buku_tamu.tahun_ajaran = '$tahun_ajaran_filter'";
    }
}

// Filter untuk Kelas
if (isset($_GET['filter_kelas']) && $_GET['filter_kelas'] != '') {
    $kelas_filter = $_GET['filter_kelas'];
    if ($kelas_filter != 'Semua') {
        $sql .= " AND anggota.kelas = '$kelas_filter'";
    }
}

// Filter untuk Keperluan
if (isset($_GET['filter_keperluan']) && $_GET['filter_keperluan'] != '') {
    $keperluan_filter = $_GET['filter_keperluan'];
    if ($keperluan_filter != 'Semua') {
        $sql .= " AND buku_tamu.keperluan LIKE '%" . mysqli_real_escape_string($conn, $keperluan_filter) . "%'";
    }
}

// Filter untuk Tanggal Kunjungan
if (isset($_GET['filter_tanggal_mulai']) && $_GET['filter_tanggal_mulai'] != '' && isset($_GET['filter_tanggal_akhir']) && $_GET['filter_tanggal_akhir'] != '') {
    $tanggal_mulai = $_GET['filter_tanggal_mulai'];
    $tanggal_akhir = $_GET['filter_tanggal_akhir'];
    $sql .= " AND buku_tamu.tanggal_kunjungan BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";
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
    <style>
        /* Aturan CSS untuk menghilangkan bayangan pada tabel */
        .table-no-shadow {
            box-shadow: none !important;
        }
    </style>
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

    <!-- Form Pencarian dan Filter -->
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
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahBukuTamu">Tambah</button>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalFilter">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped mb-0 table-no-shadow">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Tahun Ajaran</th>
                                    <th scope="col">Keperluan</th>
                                    <th scope="col">Tanggal Kunjungan</th>
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
        echo "<td>" . htmlspecialchars($row['tahun_ajaran']) . "</td>";
        echo "<td>" . htmlspecialchars(ucfirst($row['keperluan'])) . "</td>";
        echo "<td>" . htmlspecialchars($row['tanggal_kunjungan']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>Tidak ada data buku tamu</td></tr>";
}

mysqli_close($conn);
?>
<!-- Modal Detail Buku Tamu -->
<div class="modal fade" id="modalDetailBukuTamu" tabindex="-1" aria-labelledby="modalDetailBukuTamuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailBukuTamuLabel">Detail Buku Tamu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent"></div>
                <input type="hidden" id="idBukuTamu" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-danger" id="hapusBukuTamuBtn">Hapus</button>
                <button type="button" class="btn btn-primary" id="editBukuTamuBtn">Edit</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1" aria-labelledby="modalFilterLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFilterLabel">Filter Buku Tamu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="filterTahunAjaran" class="form-label">Tahun Ajaran</label>
                            <select class="form-select" id="filterTahunAjaran" name="filter_tahun_ajaran">
                            <option value="" <?php if (!empty($_GET['filter_tahun_ajaran']) && $_GET['filter_tahun_ajaran'] == '') echo 'selected'; ?>>Semua</option>
                            <?php
                            include '../koneksi.php';
                            $sql = "SELECT DISTINCT tahun_ajaran FROM anggota";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $selected = '';
                                if (!empty($_GET['filter_tahun_ajaran']) && $_GET['filter_tahun_ajaran'] == $row['tahun_ajaran']) {
                                    $selected = 'selected';
                                }
                                echo "<option value='" . htmlspecialchars($row['tahun_ajaran']) . "' $selected>" . htmlspecialchars($row['tahun_ajaran']) . "</option>";
                            }
                            mysqli_close($conn);
                            ?>
                        </select>

                        </div>
                        <div class="col-md-4">
                            <label for="filterKelas" class="form-label">Kelas</label>
                            <select class="form-select" id="filterKelas" name="filter_kelas">
                                <option value="" selected>Semua</option>
                                <?php
                                include '../koneksi.php';
                                $sql = "SELECT DISTINCT kelas FROM anggota";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . htmlspecialchars($row['kelas']) . "'>" . htmlspecialchars($row['kelas']) . "</option>";
                                }
                                mysqli_close($conn);
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filterKeperluan" class="form-label">Keperluan</label>
                            <select class="form-select" id="filterKeperluan" name="filter_keperluan">
                                <option value="" selected>Semua</option>
                                <option value="Meminjam">Meminjam</option>
                                <option value="Mengunjungi">Mengunjungi</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="filterTanggalMulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="filterTanggalMulai" name="filter_tanggal_mulai">
                        </div>
                        <div class="col-md-6">
                            <label for="filterTanggalAkhir" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="filterTanggalAkhir" name="filter_tanggal_akhir">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Buku Tamu -->
<div class="modal fade" id="modalTambahBukuTamu" tabindex="-1" aria-labelledby="modalTambahBukuTamuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahBukuTamuLabel">Tambah Buku Tamu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="iframeTambahBukuTamu" src="test.php" width="100%" height="450px" style="border:none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src="../script/sidebar.js"></script>

<script>
    // Mendengarkan pesan dari iframe
    window.addEventListener('message', function(event) {
        if (event.data === 'success') {
            // Redirect ke halaman buku_tamu.php setelah sukses
            window.location.href = 'buku_tamu.php';
        }
    });
</script>
<!-- Script untuk Detail, Edit, dan Hapus Buku Tamu -->
<script>
    // Mendengarkan klik pada baris tabel untuk menampilkan detail buku tamu
    document.addEventListener('DOMContentLoaded', function() {
        const modalDetail = new bootstrap.Modal(document.getElementById('modalDetailBukuTamu'));
        const detailContent = document.getElementById('detailContent');
        const idBukuTamuInput = document.getElementById('idBukuTamu');
        const hapusBtn = document.getElementById('hapusBukuTamuBtn');
        const editBtn = document.getElementById('editBukuTamuBtn');

        // Event listener untuk setiap baris tabel
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                fetchDetail(id);
            });
        });

        // Fungsi untuk mengambil dan menampilkan detail buku tamu
        function fetchDetail(id) {
            fetch(`../fungsi/detail_buku_tamu.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    detailContent.innerHTML = data;
                    idBukuTamuInput.value = id; // Simpan ID buku tamu dalam input tersembunyi
                    modalDetail.show();
                })
                .catch(error => console.error('Error:', error));
        }

        // Event listener untuk tombol hapus
        hapusBtn.addEventListener('click', function() {
            const id = idBukuTamuInput.value;
            if (confirm('Apakah Anda yakin ingin menghapus data buku tamu ini?')) {
                fetch(`../fungsi/hapus_buku_tamu.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${id}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        alert('Data buku tamu berhasil dihapus.');
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus data buku tamu.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        // Event listener untuk tombol edit
        editBtn.addEventListener('click', function() {
            const id = idBukuTamuInput.value;
            window.location.href = `../fungsi/edit_buku_tamu.php?id=${id}`;
        });
    });
</script>


</body>
</html>
