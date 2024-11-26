<?php
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $keperluan = $_POST['keperluan'];

    // Query untuk mengupdate keperluan buku tamu berdasarkan ID
    $sql = "UPDATE buku_tamu SET keperluan = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $keperluan, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Data berhasil diupdate!');
            window.location.href = '../views/buku_tamu.php';
            </script>";
    } else {
        echo "<script>
            alert('Gagal mengupdate data!');
            window.location.href = '../views/buku_tamu.php';
            </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data buku tamu berdasarkan ID
    $sql = "SELECT * FROM buku_tamu WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Buku Tamu</title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
        <div class="container mt-5">
            <h2>Edit Buku Tamu</h2>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                <div class="mb-3">
                    <label for="keperluan" class="form-label">Keperluan</label>
                    <select class="form-control" id="keperluan" name="keperluan">
                        <option value="mengunjungi" <?php echo $row['keperluan'] == 'mengunjungi' ? 'selected' : ''; ?>>Mengunjungi</option>
                        <option value="meminjam" <?php echo $row['keperluan'] == 'meminjam' ? 'selected' : ''; ?>>Meminjam</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="../views/buku_tamu.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        echo "Data buku tamu tidak ditemukan.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "ID buku tamu tidak valid.";
}
?>
