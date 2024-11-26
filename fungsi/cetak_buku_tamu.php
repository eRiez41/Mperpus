<?php
require('../library/fpdf/fpdf.php');
include '../koneksi.php';

// Get filters and search keyword from URL parameters
$filterKeperluan = isset($_GET['keperluan']) ? $_GET['keperluan'] : '';
$filterKelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$filterTanggalMulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$filterTanggalSelesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '';
$searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Query to fetch guest book data
$sql = "SELECT buku_tamu.*, anggota.nama AS nama_anggota, anggota.kelas
        FROM buku_tamu 
        JOIN anggota ON buku_tamu.id_anggota = anggota.id
        WHERE 1=1";

if ($filterKeperluan != '') {
    $sql .= " AND buku_tamu.keperluan = '$filterKeperluan'";
}

if ($filterKelas != '') {
    $sql .= " AND anggota.kelas LIKE '$filterKelas%'";
}

if ($filterTanggalMulai != '' && $filterTanggalSelesai != '') {
    $sql .= " AND buku_tamu.tanggal_kunjungan BETWEEN '$filterTanggalMulai' AND '$filterTanggalSelesai'";
}

if ($searchKeyword != '') {
    $sql .= " AND (anggota.nama LIKE '%$searchKeyword%' OR anggota.kelas LIKE '%$searchKeyword%')";
}

$sql .= " ORDER BY buku_tamu.tanggal_kunjungan DESC";

$result = mysqli_query($conn, $sql);

class PDF_MC_Table extends FPDF
{
    protected $widths;
    protected $aligns;

    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        $this->aligns = $a;
    }

    function Row($data)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        $this->CheckPageBreak($h);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->MultiCell($w, 5, $data[$i], 0, $a);
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
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
        $leftLogoPath = '../assets/kabtasik.png'; 
        $rightLogoPath = '../assets/neducitas.jpeg'; 

        $logoLWidth = 25;
        $logoLHeight = 25;
        $logoRWidth = 23;
        $logoRHeight = 25;

        $this->Image($leftLogoPath, 40, 10, $logoLWidth, $logoLHeight);
        $this->Image($rightLogoPath, $this->w - 60, 10, $logoRWidth, $logoRHeight);

        $this->SetFont('Times', 'B', 14);
        $this->Cell(0, 10, 'LAPORAN BUKU TAMU PERPUSTAKAAN', 0, 1, 'C');
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
        $yPosition = $this->GetPageHeight() - 70; 

        $this->SetY($yPosition);
        $this->SetFont('Times', '', 12);
        $this->SetX($this->GetPageWidth() / 2 + 65); 
        $this->Cell(0, 10, 'Kepala Sekolah,', 0, 1, 'L'); 
        
        $this->Ln(20);
        $this->SetFont('Times', 'B', 12);
        $this->SetX($this->GetPageWidth() / 2 + 65); 
        $this->Cell(0, 10, 'TANTAN SUHARTANA, M.Pd', 0, 1, 'L'); 
        
        $this->Ln(-2); 
        $this->SetX($this->GetPageWidth() / 2 + 65); 
        $this->Cell($this->GetStringWidth('TANTAN SUHARTANA, M.Pd'), 0, '', 1, 1, 'L'); 
        
        $this->Ln(-2); 
        $this->SetFont('Times', '', 12);
        $this->SetX($this->GetPageWidth() / 2 + 65); 
        $this->Cell(0, 10, 'NIP. 196902031997021002', 0, 0, 'L'); 
        
        $this->SetY($yPosition);
        $this->SetFont('Times', '', 12);
        $this->SetX($this->GetPageWidth() / 2 - 26 - $this->GetStringWidth('Pengelola Perpustakaan, ELI TARLIANI, S.Pd')); 
        $this->Cell(0, 10, 'Pengelola Perpustakaan,', 0, 1, 'L'); 
        
        $this->Ln(20);
        $this->SetFont('Times', 'B', 12);
        $this->SetX($this->GetPageWidth() / 2 - 65 - $this->GetStringWidth('ELI TARLIANI, S.Pd')); 
        $this->Cell(0, 10, 'ELI TARLIANI, S.Pd', 0, 1, 'L'); 
        
        $this->Ln(-2); 
        $this->SetX($this->GetPageWidth() / 2 - 65 - $this->GetStringWidth('ELI TARLIANI, S.Pd')); 
        $this->Cell($this->GetStringWidth('ELI TARLIANI, S.Pd'), 0, '', 1, 1, 'L'); 
        
        $this->Ln(-2); 
        $this->SetFont('Times', '', 12);
        $this->SetX($this->GetPageWidth() / 2 - 60 - $this->GetStringWidth('NIP. 197303142006042013')); 
        $this->Cell(0, 10, 'NIP. 197303142006042013', 0, 0, 'L'); 
    }

    function BasicTable($header, $data)
    {
        $this->SetFillColor(200, 220, 255);
        $this->SetFont('Times', 'B', 10);

        $columnWidths = [10, 45, 20, 40, 40];
        $this->SetWidths($columnWidths);

        $totalWidth = array_sum($columnWidths);
        $xPosition = ($this->w - $totalWidth) / 2;

        $this->SetX($xPosition);
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($columnWidths[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFont('Times', '', 12);
        $no = 1;

        foreach ($data as $row) {
            $this->SetX($xPosition);
            $rowData = array_merge([$no++], $row);
            $this->Row($rowData);
        }
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Times', '', 10);

$header = ['No', 'Nama Anggota', 'Kelas', 'Tanggal Kunjungan', 'Keperluan'];

$data = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            $row['nama_anggota'],
            $row['kelas'],
            $row['tanggal_kunjungan'],
            $row['keperluan'],
        ];
    }
} else {
    $data[] = ['Tidak ada data buku tamu', '', '', '', ''];
}

mysqli_close($conn);

$pdf->BasicTable($header, $data);

$pdf->Output('I', 'Daftar_Buku_Tamu.pdf');
?>
