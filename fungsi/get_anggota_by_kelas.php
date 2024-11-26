<?php
include '../koneksi.php';

if (isset($_GET['kelas'])) {
    $kelas = $_GET['kelas'];
    
    // Query untuk mengambil anggota berdasarkan kelas
    $sql = "SELECT id, nama FROM anggota WHERE kelas = '$kelas'";
    $result = mysqli_query($conn, $sql);
    
    $options = "<option value='' selected disabled>Pilih Anggota</option>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nama']) . "</option>";
        }
    } else {
        $options .= "<option value='' disabled>Tidak ada anggota</option>";
    }
    
    echo $options;
} else {
    echo "<option value='' selected disabled>Pilih Anggota</option>";
}

mysqli_close($conn);
?>
