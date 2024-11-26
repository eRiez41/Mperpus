<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $kd_peminjaman = $_GET['id'];

    // Query untuk mengambil data peminjaman dan kelas peminjam
    $sql = "SELECT peminjaman.*, anggota.nama AS nama_peminjam, anggota.kelas AS kelas_peminjam
            FROM peminjaman
            JOIN anggota ON peminjaman.id = anggota.id
            WHERE peminjaman.kd_peminjaman = '$kd_peminjaman'";
    $result = mysqli_query($conn, $sql);
    $data_peminjaman = mysqli_fetch_assoc($result);

    // Query untuk mengambil detail buku yang dipinjam beserta statusnya
    $sql_detail = "SELECT buku.judul, peminjaman_detail.status
                   FROM peminjaman_detail
                   JOIN buku ON peminjaman_detail.kode_buku = buku.kode_buku
                   WHERE peminjaman_detail.kd_peminjaman = '$kd_peminjaman'";
    $result_detail = mysqli_query($conn, $sql_detail);
    $buku = [];
    while ($row = mysqli_fetch_assoc($result_detail)) {
        $buku[] = $row;
    }

    // Menampilkan data dalam format HTML
    echo "<h5>Nama Peminjam: " . htmlspecialchars($data_peminjaman['nama_peminjam']) . "</h5>";
    echo "<p>Kelas Peminjam: " . htmlspecialchars($data_peminjaman['kelas_peminjam']) . "</p>";
    echo "<p>Tanggal Pinjam: " . htmlspecialchars($data_peminjaman['tanggal_pinjam']) . "</p>";
    echo "<p>Status: " . htmlspecialchars($data_peminjaman['status']) . "</p>";
    echo "<p>Kategori: " . htmlspecialchars($data_peminjaman['kategori']) . "</p>";
    echo "<h6>Buku yang Dipinjam:</h6>";
    echo "<ul>";
    foreach ($buku as $b) {
        echo "<li>" . htmlspecialchars($b['judul']) . " : " . htmlspecialchars($b['status']) . "</li>";
    }
    echo "</ul>";

    // Menambahkan tombol Edit dan Hapus
    echo "<div class='mt-3'>";
    echo "<button class='btn btn-primary me-2' id='editPeminjaman' data-id='$kd_peminjaman'>Edit</button>";
    echo "<button class='btn btn-danger' id='hapusPeminjaman' data-id='$kd_peminjaman'>Hapus</button>";
    echo "</div>";
} else {
    echo "Data tidak ditemukan.";
}

mysqli_close($conn);
?>
