<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM anggota WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='row'>";
        echo "<div class='col-md-4'>";
        if (!empty($row['foto_siswa'])) {
            echo "<img src='../uploadan/" . htmlspecialchars($row['foto_siswa']) . "' alt='" . htmlspecialchars($row['nama']) . "' class='img-fluid'>";
        } else {
            echo "<img src='../uploadan/default.jpg' alt='Default Image' class='img-fluid'>"; // Default image if no photo available
        }
        echo "</div>";
        echo "<div class='col-md-8'>";
        echo "<p><strong>NISN:</strong> " . htmlspecialchars($row['nisn']) . "</p>";
        echo "<p><strong>Nama:</strong> " . htmlspecialchars($row['nama']) . "</p>";
        echo "<p><strong>Alamat:</strong> " . htmlspecialchars($row['alamat']) . "</p>";
        echo "<p><strong>Telepon:</strong> " . htmlspecialchars($row['telepon']) . "</p>";
        echo "<p><strong>Kelas:</strong> " . htmlspecialchars($row['kelas']) . "</p>";
        echo "<p><strong>Tahun Ajaran:</strong> " . htmlspecialchars($row['tahun_ajaran']) . "</p>";
        echo "<div class='mt-3'>";
        echo "<button class='btn btn-primary me-2' id='btnEditAnggota' data-id='" . htmlspecialchars($row['id']) . "'>Edit</button>";
        echo "<button class='btn btn-danger' id='btnHapusAnggota' data-id='" . htmlspecialchars($row['id']) . "'>Hapus</button>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

        // Menambahkan script untuk tombol Edit dan Hapus
        echo "<script>
            document.getElementById('btnEditAnggota').addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                window.location.href = 'edit_anggota.php?id=' + encodeURIComponent(id);
            });

            document.getElementById('btnHapusAnggota').addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                if (confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
                    window.location.href = '../fungsi/delete_anggota.php?id=' + encodeURIComponent(id);
                }
            });

        </script>";
    } else {
        echo "<p>Data anggota tidak ditemukan.</p>";
    }
    
    mysqli_close($conn);
} else {
    echo "<p>Parameter tidak valid.</p>";
}
?>
