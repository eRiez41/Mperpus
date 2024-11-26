<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mulai output buffering
ob_start();

// Include file koneksi database
include '../koneksi.php';
require('../library/fpdf/FPDF_EXT.php');

// Periksa apakah ada data POST yang diterima
if (isset($_POST['kode_buku'])) {
    // Peroleh kode buku dari data POST
    $kode_buku = $_POST['kode_buku'];

    // Ambil detail buku dari database
    $sql = "SELECT * FROM buku WHERE kode_buku='$kode_buku'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Detail buku berhasil diambil
        $judul = $row['judul'];
        $penerbit = $row['penerbit'];
        $tahun_terbit = $row['tahun_terbit'];
        $kategori = $row['kategori'];

        // Ambil jumlah label dari data POST
        $jumlah_label = isset($_POST['jumlah_label']) ? intval($_POST['jumlah_label']) : 1;

        // Lakukan proses pembuatan label PDF di sini
        $pdf = new FPDF_EXT('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);

        // Ukuran label
        $label_width = 75;
        $label_height = 37;

        // Jumlah label per baris dan kolom
        $labels_per_row = floor(210 / $label_width);
        $labels_per_col = floor(297 / ($label_height + 2)); // Mengurangi jarak antar label

        // Margin
        $margin_x = (210 - ($labels_per_row * $label_width)) / 2;
        $margin_y = 10; // Jarak atas halaman, bisa diatur sesuai kebutuhan

        // Jarak antar label untuk menghemat kertas
        $gap = 2; // Jarak antar label

        // Inisialisasi posisi label
        $x = $margin_x;
        $y = $margin_y;

        for ($i = 0; $i < $jumlah_label; $i++) {
            // Gambar frame garis putus-putus
            $pdf->SetDrawColor(0, 0, 0); // Warna hitam
            $pdf->SetLineWidth(0.2);
            $pdf->SetDash(1, 1); // Garis putus-putus

            // Tambahkan frame label
            $pdf->Rect($x, $y, $label_width, $label_height);

            // Tambahkan detail buku ke label
            $pdf->SetXY($x + 2, $y + 2); // Margin dalam label
            $pdf->SetDash(); // Hapus garis putus-putus
            $pdf->MultiCell($label_width - 4, 5, "Kode Buku: $kode_buku\nJudul: $judul\nPenerbit: $penerbit\nTahun Terbit: $tahun_terbit\nKategori: $kategori", 0, 'L');

            // Pindah ke posisi label berikutnya
            $x += $label_width;
            if ($x + $label_width > 210) {
                $x = $margin_x;
                $y += $label_height + $gap;
                if ($y + $label_height > 297 - $margin_y) {
                    // Tambahkan halaman baru jika perlu
                    $pdf->AddPage();
                    $x = $margin_x;
                    $y = $margin_y;
                }
            }
        }

        // Hentikan output buffering dan buang isinya
        ob_end_clean();

        // Output file PDF ke browser
        $pdf->Output();
    } else {
        // Jika detail buku tidak ditemukan
        ob_end_clean();
        echo "Detail buku tidak ditemukan.";
    }

    // Tutup koneksi database
    mysqli_close($conn);
} else {
    // Hentikan output buffering dan tampilkan pesan kesalahan
    ob_end_clean();
    echo "Tidak ada data POST yang diterima.";
}
?>
