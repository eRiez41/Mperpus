<?php
// Include file koneksi.php untuk menghubungkan ke database
include '../koneksi.php';

// Inisialisasi variabel filter dengan nilai awal kosong
$selected_tahun = '';
$selected_kelas = '';
$filter_keperluan = '';
$filter_tanggal_awal = '';
$filter_tanggal_akhir = '';

// Query untuk mengambil data tahun_ajaran dari tabel buku_tamu
$query_tahun = "SELECT DISTINCT tahun_ajaran FROM buku_tamu";

$result_tahun = $conn->query($query_tahun);

if (!$result_tahun) {
    die("Query error: " . $conn->error);
}

// Mengumpulkan hasil query menjadi array
$tahun_ajaran = array();
while ($row = $result_tahun->fetch_assoc()) {
    $tahun_ajaran[] = $row['tahun_ajaran'];
}

// Query untuk mengambil data kelas dari tabel anggota berdasarkan id_anggota
$query_kelas = "SELECT DISTINCT kelas FROM anggota";

$result_kelas = $conn->query($query_kelas);

if (!$result_kelas) {
    die("Query error: " . $conn->error);
}

// Mengumpulkan hasil query kelas menjadi array
$kelas = array();
while ($row = $result_kelas->fetch_assoc()) {
    $kelas[] = $row['kelas'];
}

// Menutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Filter Buku Tamu</title>
    <!-- Load Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Load jQuery (required for Bootstrap JS) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Load Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Filter Buku Tamu</h2>
        <form action="buku_tamu.php" method="get">
            <div class="form-group">
                <label for="tahun_ajaran">Tahun Ajaran:</label>
                <select name="tahun_ajaran" class="form-control">
                    <option value="">Pilih Tahun Ajaran</option>
                    <?php foreach ($tahun_ajaran as $tahun) : ?>
                        <option value="<?php echo $tahun; ?>" <?php echo ($tahun === $selected_tahun) ? 'selected' : ''; ?>><?php echo $tahun; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="kelas">Kelas:</label>
                <select name="kelas" class="form-control">
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($kelas as $kls) : ?>
                        <option value="<?php echo $kls; ?>" <?php echo ($kls === $selected_kelas) ? 'selected' : ''; ?>><?php echo $kls; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="keperluan">Keperluan:</label>
                <select name="keperluan" class="form-control">
                    <option value="">Pilih Keperluan</option>
                    <option value="mengunjungi" <?php echo ($filter_keperluan === 'mengunjungi') ? 'selected' : ''; ?>>Mengunjungi</option>
                    <option value="meminjam" <?php echo ($filter_keperluan === 'meminjam') ? 'selected' : ''; ?>>Meminjam</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tanggal_awal">Tanggal Awal:</label>
                <input type="date" name="tanggal_awal" class="form-control" value="<?php echo $filter_tanggal_awal; ?>">
            </div>
            <div class="form-group">
                <label for="tanggal_akhir">Tanggal Akhir:</label>
                <input type="date" name="tanggal_akhir" class="form-control" value="<?php echo $filter_tanggal_akhir; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
</body>
</html>
