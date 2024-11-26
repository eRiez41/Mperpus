<?php
include '../koneksi.php';
require '../library/fpdf/fpdf.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT buku_tamu.*, anggota.nama AS nama_anggota, anggota.kelas 
            FROM buku_tamu 
            JOIN anggota ON buku_tamu.id_anggota = anggota.id 
            WHERE buku_tamu.id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $bukuTamu = mysqli_fetch_assoc($result);

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Cell(0, 10, 'Detail Buku Tamu', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(10);

        $pdf->Cell(40, 10, 'Nama', 1);
        $pdf->Cell(0, 10, $bukuTamu['nama_anggota'], 1, 1);

        $pdf->Cell(40, 10, 'Kelas', 1);
        $pdf->Cell(0, 10, $bukuTamu['kelas'], 1, 1);

        $pdf->Cell(40, 10, 'Keperluan', 1);
        $pdf->Cell(0, 10, ucfirst($bukuTamu['keperluan']), 1, 1);

        $pdf->Cell(40, 10, 'Tanggal Kunjungan', 1);
        $pdf->Cell(0, 10, $bukuTamu['tanggal_kunjungan'], 1, 1);

        $pdf->Output();
    } else {
        echo "Data tidak ditemukan";
    }

    mysqli_close($conn);
} else {
    echo "ID tidak ditemukan";
}
?>
