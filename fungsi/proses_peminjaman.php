<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anggota = $_POST['anggota'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tahun_ajaran = $_POST['tahun_ajaran']; // Tambahkan ini untuk mendapatkan tahun ajaran dari form
    $status = 'dipinjam';
    $kategori = 'wajib';  // Karena ini hanya untuk peminjaman wajib

    // Insert into peminjaman table
    $sql_peminjaman = "INSERT INTO peminjaman (id, tanggal_pinjam, tahun_ajaran, status, kategori) 
                       VALUES ('$id_anggota', '$tanggal_pinjam', '$tahun_ajaran', '$status', '$kategori')";
    if (mysqli_query($conn, $sql_peminjaman)) {
        $kd_peminjaman = mysqli_insert_id($conn);

        // Insert into peminjaman_detail table
        foreach ($_POST['buku'] as $kode_buku) {
            $sql_detail = "INSERT INTO peminjaman_detail (kd_peminjaman, kode_buku) VALUES ('$kd_peminjaman', '$kode_buku')";
            mysqli_query($conn, $sql_detail);
        }

        // Redirect to peminjaman_buku.php
        header("Location: ../views/peminjaman_buku.php");
        exit();
    } else {
        echo "Error: " . $sql_peminjaman . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
