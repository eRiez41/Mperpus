<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM buku_tamu WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $bukuTamu = mysqli_fetch_assoc($result);
    } else {
        echo "Data tidak ditemukan";
        exit;
    }
} else {
    echo "ID tidak ditemukan";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sederhana</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>

    <?php include '../includes/sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <?php include '../includes/navbar.php'; ?>

        <!-- Main Content -->
        <div class="dashboard-header">
            <h2>Edit Buku Tamu</h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="formEditBukuTamu">
                            <input type="hidden" name="id" value="<?php echo $bukuTamu['id']; ?>">
                            <div class="mb-3">
                                <label for="id_anggota" class="form-label">Nama</label>
                                <select class="form-select" id="id_anggota" name="id_anggota" required>
                                    <?php
                                    $queryAnggota = "SELECT id, nama FROM anggota";
                                    $resultAnggota = mysqli_query($conn, $queryAnggota);
                                    while ($rowAnggota = mysqli_fetch_assoc($resultAnggota)) {
                                        $selected = ($rowAnggota['id'] == $bukuTamu['id_anggota']) ? 'selected' : '';
                                        echo "<option value='" . $rowAnggota['id'] . "' $selected>" . htmlspecialchars($rowAnggota['nama']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="keperluan" class="form-label">Keperluan</label>
                                <select class="form-select" id="keperluan" name="keperluan" required>
                                    <option value="meminjam" <?php echo ($bukuTamu['keperluan'] == 'meminjam') ? 'selected' : ''; ?>>Meminjam</option>
                                    <option value="mengunjungi" <?php echo ($bukuTamu['keperluan'] == 'mengunjungi') ? 'selected' : ''; ?>>Mengunjungi</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
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
$(document).ready(function() {
    $('#formEditBukuTamu').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '../fungsi/update_buku_tamu.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response == 'success') {
                    alert('Data buku tamu berhasil diperbarui');
                    window.location.href = 'buku_tamu.php'; // Redirect ke halaman utama setelah update berhasil
                } else {
                    alert('Gagal memperbarui data buku tamu');
                }
            }
        });
    });
});
</script>

</body>
</html>
