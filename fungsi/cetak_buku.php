<?php
require('../library/fpdf/fpdf.php');
include '../koneksi.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$tahun_ajaran = isset($_GET['tahun_ajaran']) ? $_GET['tahun_ajaran'] : '';
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';

$sql = "SELECT * FROM buku WHERE 1=1";
if ($keyword != '') {
    $sql .= " AND judul LIKE '%$keyword%'";
}
if ($kategori != '') {
    $sql .= " AND kategori = '$kategori'";
}
if ($tahun_ajaran != '') {
    $sql .= " AND tahun_ajaran = '$tahun_ajaran'";
}
if ($kelas != '') {
    $sql .= " AND kelas = '$kelas'";
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
        for($i=0;$i<count($data);$i++)
            $nb = max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h = 5*$nb;
        // Issue a page break first if needed
        $this->CheckPageBreak($h);
        // Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            // Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            // Draw the border
            $this->Rect($x,$y,$w,$h);
            // Print the text
            $this->MultiCell($w,5,$data[$i],0,$a);
            // Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        // Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        // If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        // Compute the number of lines a MultiCell of width w will take
        if(!isset($this->CurrentFont))
            $this->Error('No font has been set');
        $cw = $this->CurrentFont['cw'];
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r",'',(string)$txt);
        $nb = strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while($i<$nb)
        {
            $c = $s[$i];
            if($c=="\n")
            {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep = $i;
            $l += $cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i = $sep+1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
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
        $this->Cell(0, 10, 'LAPORAN DAFTAR BUKU PERPUSTAKAAN', 0, 1, 'C');
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
    
    // Set width for each column
    $columnWidths = [7, 22, 50, 30, 25, 32, 15, 25, 25, 25, 30];
    $this->SetWidths($columnWidths);

    // Calculate total width of the table
    $totalWidth = array_sum($columnWidths);
    // Get the X coordinate to center the table
    $xPosition = ($this->w - $totalWidth) / 2;

    // Draw table header
    $this->SetX($xPosition);
    for ($i = 0; $i < count($header); $i++) {
        $this->Cell($columnWidths[$i], 7, $header[$i], 1, 0, 'C', true);
    }
    $this->Ln();

    // Set font and data row counter
    $this->SetFont('Times', '', 12);
    $no = 1;

    // Draw table data
    foreach ($data as $row) {
        // Set X position to start drawing data row
        $this->SetX($xPosition);
        // Merge data row with incrementing number
        $rowData = array_merge([$no++], $row);
        // Draw data row
        $this->Row($rowData);
    }
}

}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Times', '', 10);

$header = ['No', 'Kode Buku', 'Judul', 'Penerbit', 'Tahun Terbit', 'ISBN', 'Lembar', 'Jumlah Buku', 'Tahun Masuk', 'Harga Buku','Penulis'];

$data = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            $row['kode_buku'],
            $row['judul'],
            $row['penerbit'],
            $row['tahun_terbit'],
            $row['isbn'],
            $row['jumlah_lembar'],
            $row['jumlah_buku'],
            $row['tahun_masuk'],
            $row['harga_buku'],
            $row['penulis']
        ];
    }
} else {
    $data[] = ['Tidak ada data buku', '', '', '', '', '', '', '', ''];
}

mysqli_close($conn);

$pdf->BasicTable($header, $data);

$pdf->Output('I', 'Daftar_Buku.pdf');
?>
