<?php
require('../library/fpdf/fpdf.php');
include '../koneksi.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';

$sql = "SELECT * FROM anggota WHERE 1=1";
if ($keyword != '') {
    $sql .= " AND (nisn LIKE '%$keyword%' OR nama LIKE '%$keyword%')";
}
if ($kelas != '') {
    $sql .= " AND kelas LIKE '$kelas%'";
}
$result = mysqli_query($conn, $sql);

class PDF_MC_Table extends FPDF
{
    protected $widths;
    protected $aligns;

    function SetWidths($w)
    {
        // Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        // Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data)
    {
        // Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        // Issue a page break first if needed
        $this->CheckPageBreak($h);
        // Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            // Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            // Draw the border
            $this->Rect($x, $y, $w, $h);
            // Print the text
            $this->MultiCell($w, 5, $data[$i], 0, $a);
            // Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        // Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        // If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        // Compute the number of lines a MultiCell of width w will take
        if (!isset($this->CurrentFont))
            $this->Error('No font has been set');
        $cw = $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', (string)$txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}

class PDF extends PDF_MC_Table
{
    function Header()
    {
        // Set path for the logos
        $leftLogoPath = '../assets/kabtasik.png'; // Replace with actual path to the left logo
        $rightLogoPath = '../assets/neducitas.jpeg'; // Replace with actual path to the right logo

        // Set logo size
        $logoLWidth = 25;
        $logoLHeight = 25;
        $logoRWidth = 23;
        $logoRHeight = 25;

        // Left Logo
        $this->Image($leftLogoPath, 40, 10, $logoLWidth, $logoLHeight);

        // Right Logo
        $this->Image($rightLogoPath, $this->w - 60, 10, $logoRWidth, $logoRHeight);

        $this->SetFont('Times', 'B', 14);
        $this->Cell(0, 10, 'LAPORAN DAFTAR ANGGOTA PERPUSTAKAAN', 0, 1, 'C');
        $this->Ln(-3);
        $this->Cell(0, 10, 'SMPN 2 CISAYONG', 0, 1, 'C');
        $this->Ln(-3);
        $this->SetFont('Times', 'I', 14);
        $this->Cell(0, 10, 'Jl Cigorowong Desa Sukamukti Kec.Cisayong Kab. Tasikmalaya 46153', 0, 1, 'C');
        $this->SetFont('Times', '', 10);
        $this->Cell(0, 10, 'Tanggal: ' . date('d-m-Y'), 0, 1, 'R');
        $this->Ln(2);
    }

    function Footer()
    {
        // Signature section
        $yPosition = $this->GetPageHeight() - 70; // Adjust this value as needed

        // Kepala Sekolah
        $this->SetY($yPosition);
        $this->SetFont('Times', '', 12);
        $this->SetX($this->GetPageWidth() / 2 + 65); // Adjust this value as needed
        $this->Cell(0, 10, 'Kepala Sekolah,', 0, 1, 'L'); // Align to the left

        $this->Ln(20);
        $this->SetFont('Times', 'B', 12);
        $this->SetX($this->GetPageWidth() / 2 + 65); // Adjust this value as needed
        $this->Cell(0, 10, 'TANTAN SUHARTANA, M.Pd', 0, 1, 'L'); // Align to the left

        $this->Ln(-2); // Adjust the space before the separator line
        // Draw a separator line
        $this->SetX($this->GetPageWidth() / 2 + 65); // Adjust this value as needed
        $this->Cell($this->GetStringWidth('TANTAN SUHARTANA, M.Pd'), 0, '', 1, 1, 'L'); // Line with the length of the name

        $this->Ln(-2); // Adjust the space after the separator line
        $this->SetFont('Times', '', 12);
        $this->SetX($this->GetPageWidth() / 2 + 65); // Adjust this value as needed
        $this->Cell(0, 10, 'NIP. 196902031997021002', 0, 0, 'L'); // Align to the left

        // Pengelola Perpustakaan
        $this->SetY($yPosition);
        $this->SetFont('Times', '', 12);
        $this->SetX($this->GetPageWidth() / 2 - 26 - $this->GetStringWidth('Pengelola Perpustakaan, ELI TARLIANI, S.Pd')); // Adjust this value as needed
        $this->Cell(0, 10, 'Pengelola Perpustakaan,', 0, 1, 'L'); // Align to the left

        $this->Ln(20);
        $this->SetFont('Times', 'B', 12);
        $this->SetX($this->GetPageWidth() / 2 - 65 - $this->GetStringWidth('ELI TARLIANI, S.Pd')); // Adjust this value as needed
        $this->Cell(0, 10, 'ELI TARLIANI, S.Pd', 0, 1, 'L'); // Align to the left

        $this->Ln(-2); // Adjust the space before the separator line
        // Draw a separator line
        $this->SetX($this->GetPageWidth() / 2 - 65 - $this->GetStringWidth('ELI TARLIANI, S.Pd')); // Adjust this value as needed
        $this->Cell($this->GetStringWidth('ELI TARLIANI, S.Pd'), 0, '', 1, 1, 'L'); // Line with the length of the name

        $this->Ln(-2); // Adjust the space after the separator line
        $this->SetFont('Times', '', 12);
        $this->SetX($this->GetPageWidth() / 2 - 60 - $this->GetStringWidth('NIP. 197303142006042013')); // Adjust this value as needed
        $this->Cell(0, 10, 'NIP. 197303142006042013', 0, 0, 'L'); // Align to the left
    }

    function BasicTable($header, $data)
    {
        $this->SetFillColor(200, 220, 255);
        $this->SetFont('Times', 'B', 10);
        $widths = [10, 30, 60, 35, 30, 30];
        $this->SetWidths($widths);

        // Calculate total width of the table
        $totalWidth = array_sum($widths);
        // Get the X coordinate to center the table
        $this->SetX(($this->w - $totalWidth) / 2);

        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($widths[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFont('Times', '', 12);
        $no = 1;
        $rowCount = 0; // Track number of rows

        foreach ($data as $row) {
            // Center each row
            $this->SetX(($this->w - $totalWidth) / 2);
            if (count($row) == 5) { // Ensure there are 5 columns in each row
                $this->Row(array_merge([$no++], $row));
                $rowCount++;
                // Add a new page if rowCount reaches 15
                if ($rowCount == 15) {
                    $this->AddPage($this->CurOrientation);
                    $this->SetFont('Times', 'B', 10);
                    // Print header again
                    for ($i = 0; $i < count($header); $i++) {
                        $this->Cell($widths[$i], 7, $header[$i], 1, 0, 'C', true);
                    }
                    $this->Ln();
                    $this->SetFont('Times', '', 12);
                    $rowCount = 0; // Reset rowCount
                }
            }
        }
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Times', '', 10);

$header = ['No', 'Nama', 'NISN', 'Alamat', 'Telepon', 'Kelas'];

$data = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            $row['nama'],
            $row['nisn'],
            $row['alamat'],
            $row['telepon'],
            $row['kelas']
        ];
    }
} else {
    $data[] = ['Tidak ada data buku', '', '', '', '', '', ''];
}

mysqli_close($conn);

$pdf->BasicTable($header, $data);

$pdf->Output('I', 'Daftar_Anggota.pdf');
?>
