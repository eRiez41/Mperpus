<?php
include '../koneksi.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$tahun_ajaran = isset($_GET['tahun_ajaran']) ? $_GET['tahun_ajaran'] : '';

// Ambil data tahun ajaran yang unik dari tabel anggota
$query_tahun_ajaran = "SELECT DISTINCT tahun_ajaran FROM anggota ORDER BY tahun_ajaran DESC";
$result_tahun_ajaran = $conn->query($query_tahun_ajaran);

$query = "SELECT id, nisn, nama, kelas, tahun_ajaran FROM anggota WHERE 1=1";

if ($keyword != '') {
    $query .= " AND (nisn LIKE '%$keyword%' OR nama LIKE '%$keyword%')";
}

if ($kelas != '') {
    $query .= " AND kelas LIKE '$kelas%'";
}

if ($tahun_ajaran != '') {
    $query .= " AND tahun_ajaran LIKE '$tahun_ajaran%'";
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
            <h2>Daftar Anggota</h2>
        </div>

        <div class="container-fluid mt-4">
            <div class="mb-3">
                <form method="GET" action="">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="keyword" placeholder="Cari NISN atau Nama" value="<?php echo htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="kelas">
                                <option value="">Semua Kelas</option>
                                <!-- Ganti kelas di sini sesuai dengan kebutuhan -->
                                <option value="7" <?php if (isset($_GET['kelas']) && $_GET['kelas'] == '7') echo 'selected'; ?>>Kelas 7</option>
                                <option value="8" <?php if (isset($_GET['kelas']) && $_GET['kelas'] == '8') echo 'selected'; ?>>Kelas 8</option>
                                <option value="9" <?php if (isset($_GET['kelas']) && $_GET['kelas'] == '9') echo 'selected'; ?>>Kelas 9</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="tahun_ajaran">
                                <option value="">Semua Tahun Ajaran</option>
                                <?php
                                if ($result_tahun_ajaran->num_rows > 0) {
                                    while($row_tahun_ajaran = $result_tahun_ajaran->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row_tahun_ajaran['tahun_ajaran']) . '"';
                                        if (isset($_GET['tahun_ajaran']) && $_GET['tahun_ajaran'] == $row_tahun_ajaran['tahun_ajaran']) echo ' selected';
                                        echo '>' . htmlspecialchars($row_tahun_ajaran['tahun_ajaran']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
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
                        <th>Tahun Ajaran</th>
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
                            echo "<td>" . htmlspecialchars($row['tahun_ajaran']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Tidak ada data anggota</td></tr>";
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
                        <div id="detailAnggota">
                            <img id="fotoAnggota" src="#" alt="Foto Anggota" width="100">
                            <p id="namaAnggota"></p>
                            <p id="nisnAnggota"></p>
                            <p id="kelasAnggota"></p>
                            <p id="alamatAnggota"></p>
                            <p id="tahunAjaran"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="cetakDetailAnggota">Cetak</button>
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
                $('#cetakDetailAnggota').data('id', anggotaId);  // Store the anggotaId in the cetak button
            }
        });
    });

    $('#cetakDetailAnggota').on('click', function () {
        var anggotaId = $(this).data('id');
        window.open('../fungsi/cetak_detail_anggota.php?id=' + anggotaId, '_blank');
    });
});
</script>

</body>
</html>
