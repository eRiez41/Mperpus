<?php
include '../koneksi.php';

// Debug: Periksa koneksi
if ($conn) {
    echo "Koneksi berhasil.<br>";
} else {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Inisialisasi variabel
$data_peminjaman = null;
$result_detail = null;

// Periksa apakah parameter id ada di URL
if (isset($_GET['id'])) {
    $id_peminjam = $_GET['id'];
    echo "ID Peminjam: " . htmlspecialchars($id_peminjam) . "<br>";

    // Query untuk mengambil data peminjaman
    $sql = "SELECT peminjaman.*, anggota.nama AS nama_peminjam
            FROM peminjaman
            JOIN anggota ON peminjaman.id = anggota.id
            WHERE peminjaman.id = '$id_peminjam' AND peminjaman.status = 'dipinjam'";
    echo "SQL Query: " . htmlspecialchars($sql) . "<br>";
    
    $result = mysqli_query($conn, $sql);
    
    // Debug: Periksa hasil query peminjaman
    if ($result && mysqli_num_rows($result) > 0) {
        echo "Data peminjaman ditemukan.<br>";
        $data_peminjaman = mysqli_fetch_assoc($result);
        print_r($data_peminjaman);

        // Query untuk mengambil detail buku yang dipinjam
        $kd_peminjaman = $data_peminjaman['kd_peminjaman'];
        $sql_detail = "SELECT buku.kode_buku, buku.judul, peminjaman_detail.status
                       FROM peminjaman_detail
                       JOIN buku ON peminjaman_detail.kode_buku = buku.kode_buku
                       WHERE peminjaman_detail.kd_peminjaman = '$kd_peminjaman'";
        echo "SQL Detail Query: " . htmlspecialchars($sql_detail) . "<br>";
        
        $result_detail = mysqli_query($conn, $sql_detail);
        
        // Debug: Periksa hasil query detail
        if ($result_detail && mysqli_num_rows($result_detail) > 0) {
            echo "Detail buku ditemukan.<br>";
        } else {
            echo "Detail buku tidak ditemukan.<br>";
        }
    } else {
        echo "Data peminjaman tidak ditemukan atau peminjam tidak sedang meminjam buku apapun.<br>";
    }
} else {
    echo "Parameter ID tidak ditemukan.<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Pengembalian Buku</h3>
    <?php if ($data_peminjaman): ?>
        <p>Nama Peminjam: <?php echo htmlspecialchars($data_peminjaman['nama_peminjam']); ?></p>
        <p>Tanggal Pinjam: <?php echo htmlspecialchars($data_peminjaman['tanggal_pinjam']); ?></p>
        <p>Status: <?php echo htmlspecialchars($data_peminjaman['status']); ?></p>
        <h5>Detail Buku yang Dipinjam:</h5>
        <ul class="list-group">
            <?php if ($result_detail && mysqli_num_rows($result_detail) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result_detail)): ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($row['judul']); ?> (Status: <?php echo htmlspecialchars($row['status']); ?>)
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="list-group-item">Tidak ada buku yang dipinjam.</li>
            <?php endif; ?>
        </ul>
    <?php else: ?>
        <p>Data peminjaman tidak ditemukan atau peminjam tidak sedang meminjam buku apapun.</p>
    <?php endif; ?>
</div>
</body>
</html>

<?php
mysqli_close($conn);
?>
