<?php
// Memanggil file koneksi
include '../koneksi.php';

if (isset($_GET['id'])) {
    $kd_peminjaman = $_GET['id'];

    // Ambil data peminjaman
    $sql = "SELECT peminjaman.*, anggota.nama AS nama_peminjam
            FROM peminjaman
            JOIN anggota ON peminjaman.id = anggota.id
            WHERE peminjaman.kd_peminjaman = '$kd_peminjaman'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query error: " . mysqli_error($conn));
    }

    $data_peminjaman = mysqli_fetch_assoc($result);

    // Ambil buku yang dipinjam
    $sql_buku = "SELECT buku.kode_buku, buku.judul, peminjaman_detail.status
                 FROM peminjaman_detail
                 JOIN buku ON peminjaman_detail.kode_buku = buku.kode_buku
                 WHERE peminjaman_detail.kd_peminjaman = '$kd_peminjaman'";
    $result_buku = mysqli_query($conn, $sql_buku);

    if (!$result_buku) {
        die("Query error: " . mysqli_error($conn));
    }

    $buku_dipinjam = [];
    while ($row_buku = mysqli_fetch_assoc($result_buku)) {
        $buku_dipinjam[] = $row_buku;
    }

    // Ambil semua buku untuk dropdown
    $sql_all_buku = "SELECT kode_buku, judul FROM buku";
    $result_all_buku = mysqli_query($conn, $sql_all_buku);

    if (!$result_all_buku) {
        die("Query error: " . mysqli_error($conn));
    }

    $all_buku = [];
    while ($row_all_buku = mysqli_fetch_assoc($result_all_buku)) {
        $all_buku[] = $row_all_buku;
    }
} else {
    echo "Data tidak ditemukan.";
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peminjaman</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/peminjaman_buku.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <style>
    .buku-select {
        position: relative;
        margin-bottom: 1rem;
    }

    .buku-select .remove-buku {
        position: absolute;
        right: 10px;
        top: 25%;
        transform: translateY(-50%);
        background-color: #dc3545;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        width: auto;
        height: auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .buku-select .remove-buku:hover {
        background-color: #c82333;
    }
</style>

</head>
<body>

    <?php include '../includes/sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <?php include '../includes/navbar.php'; ?>

        <!-- Main Content -->
        <div class="container mt-4">
            <h2>Edit Peminjaman</h2>
            <form action="../fungsi/update_peminjaman.php" method="POST">
    <input type="hidden" name="kd_peminjaman" value="<?php echo htmlspecialchars($kd_peminjaman); ?>">

    <div class="mb-3">
        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
        <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" value="<?php echo htmlspecialchars($data_peminjaman['tanggal_pinjam']); ?>" required>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status Pinjam</label>
        <select class="form-control" id="status" name="status" required disabled>
            <option value="dipinjam" <?php if($data_peminjaman['status'] == 'dipinjam') echo 'selected'; ?>>Dipinjam</option>
            <option value="dikembalikan" <?php if($data_peminjaman['status'] == 'dikembalikan') echo 'selected'; ?>>Dikembalikan</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <select class="form-control" id="kategori" required disabled>
            <option value="wajib" <?php if($data_peminjaman['kategori'] == 'wajib') echo 'selected'; ?>>Wajib</option>
            <option value="opsional" <?php if($data_peminjaman['kategori'] == 'opsional') echo 'selected'; ?>>Opsional</option>
        </select>
        <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($data_peminjaman['kategori']); ?>">
    </div>

    <div class="mb-3">
        <label for="buku" class="form-label">Buku yang Dipinjam</label>
        <div id="buku-wrapper">
            <?php foreach ($buku_dipinjam as $index => $buku) { ?>
                <div class="buku-select">
                    <select class="form-control mb-2" name="buku[]">
                        <?php foreach ($all_buku as $option) { ?>
                            <option value="<?php echo $option['kode_buku']; ?>" <?php if ($buku['kode_buku'] == $option['kode_buku']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($option['judul']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <button type="button" class="remove-buku">Hapus</button>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="status_buku[]" value="<?php echo $index; ?>" <?php if ($buku['status'] == 'dikembalikan') echo 'checked'; ?>>
                        <label class="form-check-label" for="status_buku[]">
                            Dikembalikan
                        </label>
                    </div>
                </div>
            <?php } ?>
        </div>
        <button type="button" class="btn btn-success mt-2" id="add-buku">Tambah Buku</button>
    </div>

    <div class="mb-3" id="tanggal-kembali-wrapper" style="display: none;">
        <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
        <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" value="<?php echo htmlspecialchars($data_peminjaman['tanggal_pengembalian']); ?>">
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="../script/sidebar.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
   $(document).ready(function() {
    function updateRemoveButtons() {
        $('.remove-buku').each(function(index) {
            $(this).off('click').on('click', function() {
                $(this).parent('.buku-select').remove();
                updateStatus();
            });
        });
    }

    function updateStatus() {
        var allReturned = true;
        $('input[type="checkbox"][name="status_buku[]"]').each(function() {
            if (!$(this).is(':checked')) {
                allReturned = false;
            }
        });

        if (allReturned) {
            $('#tanggal-kembali-wrapper').show();
            $('#status').val('dikembalikan');
        } else {
            $('#tanggal-kembali-wrapper').hide();
            $('#status').val('dipinjam');
        }
    }

    $('#add-buku').on('click', function() {
        var bukuSelectHtml = `<div class="buku-select">
            <select class="form-control mb-2" name="buku[]">
                <?php foreach ($all_buku as $option) { ?>
                    <option value="<?php echo $option['kode_buku']; ?>"><?php echo htmlspecialchars($option['judul']); ?></option>
                <?php } ?>
            </select>
            <button type="button" class="remove-buku">Hapus</button>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="status_buku[]" value="<?php echo $index; ?>">
                <label class="form-check-label" for="status_buku[]">
                    Dikembalikan
                </label>
            </div>
        </div>`;
        $('#buku-wrapper').append(bukuSelectHtml);
        updateRemoveButtons();
    });

    $(document).on('click', '.remove-buku', function() {
        $(this).parent('.buku-select').remove();
        updateStatus();
    });

    $(document).on('change', 'input[type="checkbox"][name="status_buku[]"]', function() {
        updateStatus();
    });

    updateRemoveButtons();
    updateStatus();
});

    </script>
</body>
</html>

