<?php
// Koneksi ke database
include '../koneksi.php';

// Query untuk mengambil buku yang berkategori selain "pelajaran"
$sql = "SELECT * FROM buku WHERE kategori != 'pelajaran'";
$result = mysqli_query($conn, $sql);

// Array untuk menyimpan hasil query
$buku = array();

// Jika query berhasil dieksekusi
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $buku[] = array(
            'kode_buku' => $row['kode_buku'],
            'judul' => $row['judul']
        );
    }
}

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($buku);

// Menutup koneksi database
mysqli_close($conn);
?>
