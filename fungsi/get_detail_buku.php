<?php
include '../koneksi.php';

if (isset($_GET['kode_buku'])) {
    $kode_buku = mysqli_real_escape_string($conn, $_GET['kode_buku']);
    $sql = "SELECT * FROM buku WHERE kode_buku = '$kode_buku'";
    $result = mysqli_query($conn, $sql);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='row'>";
        echo "<div class='col-md-4'>";
        if ($row['gambar_buku']) {
            echo "<img src='../uploadan/" . htmlspecialchars($row['gambar_buku']) . "' alt='" . htmlspecialchars($row['judul']) . "' class='img-fluid'>";
        }
        echo "</div>";
        echo "<div class='col-md-8'>";
        echo "<p><strong>Kode Buku:</strong> " . htmlspecialchars($row['kode_buku']) . "</p>";
        echo "<p><strong>Judul:</strong> " . htmlspecialchars($row['judul']) . "</p>";
        echo "<p><strong>Penerbit:</strong> " . htmlspecialchars($row['penerbit']) . "</p>";
        echo "<p><strong>Tahun Terbit:</strong> " . htmlspecialchars($row['tahun_terbit']) . "</p>";
        echo "<p><strong>Kategori:</strong> " . htmlspecialchars($row['kategori']) . "</p>";
        echo "<p><strong>ISBN:</strong> " . htmlspecialchars($row['isbn']) . "</p>";
        echo "<p><strong>Jumlah Lembar:</strong> " . htmlspecialchars($row['jumlah_lembar']) . "</p>";
        echo "<p><strong>Jumlah Buku:</strong> " . htmlspecialchars($row['jumlah_buku']) . "</p>";
        echo "<p><strong>Tahun Masuk:</strong> " . htmlspecialchars($row['tahun_masuk']) . "</p>";
        echo "<p><strong>Harga Buku:</strong> " . htmlspecialchars($row['harga_buku']) . "</p>";
        echo "<p><strong>Penulis:</strong> " . htmlspecialchars($row['penulis']) . "</p>";
        echo "<p><strong>Tahun Ajaran:</strong> " . htmlspecialchars($row['tahun_ajaran']) . "</p>";
        echo "<p><strong>Kelas:</strong> " . htmlspecialchars($row['kelas']) . "</p>";
        echo "</div>";
        echo "</div>";

        // Menambahkan tombol Edit dan Hapus
        echo "<div class='mt-3'>";
        echo "<button class='btn btn-primary me-2' id='editBuku' data-id='" . htmlspecialchars($kode_buku) . "'>Edit</button>";
        echo "<button class='btn btn-danger' id='hapusBuku' data-id='" . htmlspecialchars($kode_buku) . "'>Hapus</button>";
        echo "</div>";

        // Menambahkan script untuk tombol Edit dan Hapus
        echo "<script>
            document.getElementById('editBuku').addEventListener('click', function() {
                var kode_buku = this.getAttribute('data-id');
                window.location.href = 'edit_buku.php?kode_buku=' + encodeURIComponent(kode_buku);
            });

            document.getElementById('hapusBuku').addEventListener('click', function() {
                var kode_buku = this.getAttribute('data-id');
                if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../fungsi/delete_buku.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                console.log('Response:', xhr.responseText); // Log respons dari server
                                if (xhr.responseText.trim() === 'success') {
                                    alert('Buku berhasil dihapus.');
                                    window.location.href = 'daftar_buku.php'; // Sesuaikan dengan halaman tujuan setelah penghapusan
                                } else {
                                    alert('Terjadi kesalahan: ' + xhr.responseText);
                                }
                            } else {
                                alert('Terjadi kesalahan dalam menghubungi server.');
                            }
                        }
                    };
                    xhr.send('kode_buku=' + encodeURIComponent(kode_buku));
                }
            });
        </script>";
    } else {
        echo "<p>Data buku tidak ditemukan.</p>";
    }
    
    mysqli_close($conn);
} else {
    echo "<p>Parameter tidak valid.</p>";
}
?>
