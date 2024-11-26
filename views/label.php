<?php
// Include file koneksi database
include '../koneksi.php';

// Fungsi untuk mendapatkan data buku berdasarkan filter
function getFilteredBooks($conn, $filter) {
    $sql = "SELECT * FROM buku WHERE 1";

    // Tambahkan filter jika ada
    if (!empty($filter['kategori'])) {
        if ($filter['kategori'] == 'Pelajaran') {
            $sql .= " AND kategori = 'Pelajaran'";
        } elseif ($filter['kategori'] == 'Non Pelajaran') {
            $sql .= " AND kategori != 'Pelajaran'";
        }
    }
    if (!empty($filter['tahun_ajaran'])) {
        $sql .= " AND tahun_ajaran = '" . $filter['tahun_ajaran'] . "'";
    }
    if (!empty($filter['kelas'])) {
        $sql .= " AND kelas = '" . $filter['kelas'] . "'";
    }

    // Eksekusi query
    $result = mysqli_query($conn, $sql);

    // Periksa jika query berhasil dieksekusi
    if (!$result) {
        die("Query Error: " . mysqli_error($conn));
    }

    // Mengembalikan hasil query
    return $result;
}

// Fungsi untuk mendapatkan detail buku berdasarkan kode_buku
function getBookDetail($conn, $kode_buku) {
    $sql = "SELECT * FROM buku WHERE kode_buku = '$kode_buku'";
    $result = mysqli_query($conn, $sql);

    // Periksa jika query berhasil dieksekusi
    if (!$result) {
        die("Query Error: " . mysqli_error($conn));
    }

    // Mengembalikan hasil query
    return mysqli_fetch_assoc($result);
}

// Inisialisasi filter
$filter = array(
    'kategori' => '',
    'tahun_ajaran' => '',
    'kelas' => ''
);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai filter dari form
    $filter['kategori'] = $_POST['kategori'] ?? '';
    $filter['tahun_ajaran'] = $_POST['tahun_ajaran'] ?? '';
    $filter['kelas'] = $_POST['kelas'] ?? '';
}

// Panggil fungsi untuk mendapatkan data buku berdasarkan filter
$result = getFilteredBooks($conn, $filter);

// Mendapatkan tahun ajaran dan kelas unik dari database
$tahunAjaranResult = mysqli_query($conn, "SELECT DISTINCT tahun_ajaran FROM buku");
$kelasResult = mysqli_query($conn, "SELECT DISTINCT kelas FROM buku");

// Inisialisasi variabel detail buku
$bookDetail = null;
if (isset($_POST['kode_buku']) && !empty($_POST['kode_buku'])) {
    $bookDetail = getBookDetail($conn, $_POST['kode_buku']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Label Buku</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<?php include '../includes/sidebar.php'; ?>
<div class="content">
    <?php include '../includes/navbar.php'; ?>

    <div class="dashboard-header">
        <h2>Label Buku</h2>
    </div>

    <!-- isi konten -->
    <div class="container">
        <form id="filterForm" method="POST" class="row mb-4">
            <div class="col-md-3">
                <label for="kategori" class="form-label">Kategori:</label>
                <select name="kategori" id="kategori" class="form-select">
                    <option value="">Pilih Kategori</option>
                    <option value="Pelajaran" <?php if ($filter['kategori'] == 'Pelajaran') echo 'selected'; ?>>Pelajaran</option>
                    <option value="Non Pelajaran" <?php if ($filter['kategori'] == 'Non Pelajaran') echo 'selected'; ?>>Non Pelajaran</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="tahun_ajaran" class="form-label">Tahun Ajaran:</label>
                <select name="tahun_ajaran" id="tahun_ajaran" class="form-select">
                    <option value="">Pilih Tahun Ajaran</option>
                    <?php
                    if ($tahunAjaranResult && mysqli_num_rows($tahunAjaranResult) > 0) {
                        while ($row = mysqli_fetch_assoc($tahunAjaranResult)) {
                            $selected = ($filter['tahun_ajaran'] == $row['tahun_ajaran']) ? 'selected' : '';
                            echo "<option value='{$row['tahun_ajaran']}' {$selected}>{$row['tahun_ajaran']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="kelas" class="form-label">Kelas:</label>
                <select name="kelas" id="kelas" class="form-select">
                    <option value="">Pilih Kelas</option>
                    <?php
                    if ($kelasResult && mysqli_num_rows($kelasResult) > 0) {
                        while ($row = mysqli_fetch_assoc($kelasResult)) {
                            $selected = ($filter['kelas'] == $row['kelas']) ? 'selected' : '';
                            echo "<option value='{$row['kelas']}' {$selected}>{$row['kelas']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <input type="submit" value="Filter" class="btn btn-primary w-100">
            </div>
        </form>

        <form id="bookForm" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="kode_buku" class="form-label">Pilih Buku:</label>
                <select name="kode_buku" id="kode_buku" class="form-select">
                    <option value="">Pilih Buku</option>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['kode_buku']}'>{$row['judul']} ({$row['kode_buku']})</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <input type="submit" value="Tampilkan" class="btn btn-primary">
        </form>

        <?php if ($bookDetail): ?>
            <h2>Detail Buku</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kode Buku</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun Terbit</th>
                        <th>Kategori</th>
                        <th>ISBN</th>
                        <th>Jumlah Lembar</th>
                        <th>Jumlah Buku</th>
                        <th>Tahun Masuk</th>
                        <th>Harga Buku</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $bookDetail['kode_buku']; ?></td>
                        <td><?php echo $bookDetail['judul']; ?></td>
                        <td><?php echo $bookDetail['penulis']; ?></td>
                        <td><?php echo $bookDetail['penerbit']; ?></td>
                        <td><?php echo $bookDetail['tahun_terbit']; ?></td>
                        <td><?php echo $bookDetail['kategori']; ?></td>
                        <td><?php echo $bookDetail['isbn']; ?></td>
                        <td><?php echo $bookDetail['jumlah_lembar']; ?></td>
                        <td><?php echo $bookDetail['jumlah_buku']; ?></td>
                        <td><?php echo $bookDetail['tahun_masuk']; ?></td>
                        <td><?php echo $bookDetail['harga_buku']; ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Form untuk cetak label PDF -->
            <form method="post" action="../fungsi/generate_label.php">
                <input type="hidden" name="kode_buku" value="<?php echo $bookDetail['kode_buku']; ?>">
                <div class="mb-3">
                    <label for="jumlah_label" class="form-label">Jumlah Label:</label>
                    <input type="number" class="form-control" id="jumlah_label" name="jumlah_label" min="1" value="1">
                </div>
                <input type="submit" class="btn btn-success" value="Cetak Label PDF">
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src="../script/sidebar.js"></script>
<script>
    document.getElementById('filterForm').addEventListener('submit', function() {
        setTimeout(function() {
            window.history.replaceState(null, null, window.location.pathname);
        }, 100);
    });

    document.getElementById('bookForm').addEventListener('submit', function() {
        setTimeout(function() {
            window.history.replaceState(null, null, window.location.pathname);
        }, 100);
    });
</script>
</body>
</html>
