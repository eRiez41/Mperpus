<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Query untuk menghapus anggota
    $sql = "DELETE FROM anggota WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Anggota berhasil dihapus.');
            window.location.href = '../views/daftar_anggota.php'; // Sesuaikan dengan halaman tujuan setelah penghapusan
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    mysqli_close($conn);
} else {
    echo "<p>Parameter tidak valid.</p>";
}
?>
