<?php
include '../koneksi.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';

$query = "SELECT id, nisn, nama, kelas FROM anggota WHERE 1=1";

if ($keyword != '') {
    $query .= " AND (nisn LIKE '%$keyword%' OR nama LIKE '%$keyword%')";
}

if ($kelas != '') {
    $query .= " AND kelas LIKE '$kelas%'";
}

$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/daftar_buku.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/modal.css">
</head>
<body>

    <?php include '../includes/sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <?php include '../includes/navbar.php'; ?>

        <!-- Main Content -->
        <div class="dashboard-header">
            <h2>Laporan Anggota</h2>
        </div>

        <div class="container-fluid mt-4">
            <div class="mb-3">
                <form method="GET" action="">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="keyword" placeholder="Cari NISN atau Nama" value="<?php echo htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" name="kelas">
                                <option value="">Semua Kelas</option>
                                <option value="7" <?php if (isset($_GET['kelas']) && $_GET['kelas'] == '7') echo 'selected'; ?>>Kelas 7</option>
                                <option value="8" <?php if (isset($_GET['kelas']) && $_GET['kelas'] == '8') echo 'selected'; ?>>Kelas 8</option>
                                <option value="9" <?php if (isset($_GET['kelas']) && $_GET['kelas'] == '9') echo 'selected'; ?>>Kelas 9</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <button type="button" onclick="window.open('../fungsi/cetak_anggota.php?keyword=<?php echo htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES); ?>&kelas=<?php echo htmlspecialchars($_GET['kelas'] ?? '', ENT_QUOTES); ?>', '_blank')" class="btn btn-success">Cetak</button>
                        </div>
                    </div>
                </form>
            </div>
            <table class="table table-hover">
                <thead class="table table-secondary">
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while($row = $result->fetch_assoc()) {
                            echo "<tr data-bs-toggle='modal' data-bs-target='#modalDetailAnggota' data-id='" . $row['id'] . "'>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['nisn']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Tidak ada data anggota</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Detail Anggota -->
        <div class="modal fade" id="modalDetailAnggota" tabindex="-1" aria-labelledby="modalDetailAnggotaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailAnggotaLabel">Detail Anggota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Data anggota lengkap akan dimuat di sini dengan AJAX -->
                        <div id="detailAnggotaContent" class="card">
                            <div class="card-body">
                                <!-- Content will be loaded here with AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="../script/sidebar.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
    $(document).ready(function(){
        $('#modalDetailAnggota').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var anggotaId = button.data('id');
            var modal = $(this);
            $.ajax({
                url: '../fungsi/get_detail_anggota.php',
                type: 'GET',
                data: {id: anggotaId},
                success: function(data) {
                    $('#detailAnggotaContent .card-body').html(data);
                }
            });
        });
    });
    </script>
</body>
</html>
