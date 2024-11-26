<?php
require('../library/fpdf/fpdf.php');
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "SELECT * FROM anggota WHERE id = $id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        
        // Tulisan judul "Detail Anggota" dengan tebal (bold)
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(0, 10, 'Detail Anggota', 0, 1, 'C');
        $pdf->SetFont('Times', '', 12); // Kembalikan font ke normal setelah judul
        
        $pdf->Ln(10);
        
        // Calculate the center position for the photo and text block
        $photoWidth = 40;
        $photoHeight = 50;
        $margin = 10; // Margin between photo and text
        $pageWidth = $pdf->GetPageWidth();
        $contentWidth = $photoWidth + $margin + 100; // 100 is the width for the text block
        $xPhoto = ($pageWidth - $contentWidth) / 2;
        $xText = $xPhoto + $photoWidth + $margin;
        
        // Check if foto_siswa exists and add to PDF
        if (!empty($row['foto_siswa'])) {
            $fotoPath = '../uploadan/' . $row['foto_siswa']; // Construct the full path to the image
            if (file_exists($fotoPath)) {
                $pdf->Image($fotoPath, $xPhoto, 30, $photoWidth, $photoHeight); // Adjust the position and size of the image
            }
        }

        // Add member details next to the photo
        $pdf->SetXY($xText, 30); // Set position to the right of the photo
        
        $pdf->Cell(30, 10, 'NISN:', 0, 0);
        $pdf->Cell(70, 10, $row['nisn'], 0, 1);
        
        $pdf->SetX($xText); // Align the next cell to the same left position
        $pdf->Cell(30, 10, 'Nama:', 0, 0);
        $pdf->Cell(70, 10, $row['nama'], 0, 1);
        
        $pdf->SetX($xText);
        $pdf->Cell(30, 10, 'Kelas:', 0, 0);
        $pdf->Cell(70, 10, $row['kelas'], 0, 1);
        
        $pdf->SetX($xText);
        $pdf->Cell(30, 10, 'Alamat:', 0, 0);
        $pdf->MultiCell(70, 10, $row['alamat']); // Use MultiCell for potentially longer text
        
        $pdf->SetX($xText);
        $pdf->Cell(30, 10, 'Telepon:', 0, 0);
        $pdf->Cell(70, 10, $row['telepon'], 0, 1);
        
        // Ensure no output before this line
        ob_clean(); // Clear the output buffer
        $pdf->Output();
        exit; // Ensure no further output is sent
    } else {
        echo "Data anggota tidak ditemukan.";
    }
} else {
    echo "ID anggota tidak valid.";
}
?>
