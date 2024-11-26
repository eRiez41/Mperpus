<?php
// Simpan di file test.php

include '../koneksi.php';

// Handle AJAX requests
if (isset($_GET['get_kelas_by_tahun_ajaran'])) {
    if (isset($_GET['tahun_ajaran'])) {
        $tahunAjaran = $_GET['tahun_ajaran'];
        $sql = "SELECT DISTINCT kelas FROM anggota WHERE tahun_ajaran = '$tahunAjaran'";
        $result = mysqli_query($conn, $sql);
        $options = "<option value='' selected disabled>Pilih Kelas</option>";

        if (!$result) {
            echo mysqli_error($conn); // Tampilkan pesan error jika query gagal
            exit;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='" . htmlspecialchars($row['kelas']) . "'>" . htmlspecialchars($row['kelas']) . "</option>";
        }

        echo $options;
        exit;
    }
}

if (isset($_GET['get_anggota_by_kelas'])) {
    if (isset($_GET['kelas']) && isset($_GET['tahun_ajaran'])) {
        $kelas = $_GET['kelas'];
        $tahunAjaran = $_GET['tahun_ajaran'];
        $sql = "SELECT id, nama FROM anggota WHERE kelas = '$kelas' AND tahun_ajaran = '$tahunAjaran'";
        $result = mysqli_query($conn, $sql);
        $options = "<option value='' selected disabled>Pilih Anggota</option>";

        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nama']) . "</option>";
        }

        echo $options;
        exit;
    }
}

// Handle form submission for adding guestbook entry
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_anggota']) && isset($_POST['keperluan']) && isset($_POST['tahun_ajaran'])) {
    $id_anggota = $_POST['id_anggota'];
    $keperluan = $_POST['keperluan'];
    $tahun_ajaran = $_POST['tahun_ajaran'];
    $tanggal_kunjungan = date('Y-m-d H:i:s');

    $sql = "INSERT INTO buku_tamu (id_anggota, keperluan, tahun_ajaran, tanggal_kunjungan) 
            VALUES ('$id_anggota', '$keperluan', '$tahun_ajaran', '$tanggal_kunjungan')";

    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'error';
    }
    exit;
}

// Query untuk mengambil data tahun ajaran
$sql_tahun_ajaran = "SELECT DISTINCT tahun_ajaran FROM anggota";
$result_tahun_ajaran = mysqli_query($conn, $sql_tahun_ajaran);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            align-items: center;
            background-color: #ffffff; /* Warna background putih */
        }
    </style>
</head>
<body>

                    <form id="formTambahBukuTamu">
                        <div class="mb-3">
                            <label for="tahunAjaran" class="form-label">Tahun Ajaran</label>
                            <select class="form-select" id="tahunAjaran" name="tahun_ajaran" required>
                                <option value="" selected disabled>Pilih Tahun Ajaran</option>
                                <?php
                                if ($result_tahun_ajaran && mysqli_num_rows($result_tahun_ajaran) > 0) {
                                    while ($row_tahun_ajaran = mysqli_fetch_assoc($result_tahun_ajaran)) {
                                        echo "<option value='" . htmlspecialchars($row_tahun_ajaran['tahun_ajaran']) . "'>" . htmlspecialchars($row_tahun_ajaran['tahun_ajaran']) . "</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>Tidak ada data tahun ajaran</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select class="form-select" id="kelas" name="kelas" required disabled>
                                <option value="" selected disabled>Pilih Kelas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="anggota" class="form-label">Nama Anggota</label>
                            <select class="form-select" id="anggota" name="id_anggota" required disabled>
                                <option value="" selected disabled>Pilih Anggota</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keperluan" class="form-label">Keperluan</label>
                            <select class="form-select" id="keperluan" name="keperluan" required>
                                <option value="" selected disabled>Pilih Keperluan</option>
                                <option value="meminjam">Meminjam</option>
                                <option value="mengunjungi">Mengunjungi</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('#tahunAjaran').on('change', function() {
        var tahunAjaran = $(this).val();
        if (tahunAjaran) {
            $.ajax({
                url: 'test.php',
                type: 'GET',
                data: {
                    get_kelas_by_tahun_ajaran: true,
                    tahun_ajaran: tahunAjaran
                },
                success: function(data) {
                    $('#kelas').html(data).prop('disabled', false);
                    $('#anggota').html('<option value="" selected disabled>Pilih Anggota</option>').prop('disabled', true);
                }
            });
        } else {
            $('#kelas').html('<option value="" selected disabled>Pilih Kelas</option>').prop('disabled', true);
            $('#anggota').html('<option value="" selected disabled>Pilih Anggota</option>').prop('disabled', true);
        }
    });

    $('#kelas').on('change', function() {
        var kelas = $(this).val();
        var tahunAjaran = $('#tahunAjaran').val();
        if (kelas && tahunAjaran) {
            $.ajax({
                url: 'test.php',
                type: 'GET',
                data: {
                    get_anggota_by_kelas: true,
                    kelas: kelas,
                    tahun_ajaran: tahunAjaran
                },
                success: function(data) {
                    $('#anggota').html(data).prop('disabled', false);
                }
            });
        } else {
            $('#anggota').html('<option value="" selected disabled>Pilih Anggota</option>').prop('disabled', true);
        }
    });

    $('#formTambahBukuTamu').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: 'test.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response === 'success') {
                alert('Data buku tamu berhasil ditambahkan');
                // Mengirim pesan kepada parent window (halaman buku_tamu.php)
                window.parent.postMessage('success', '*');
            } else {
                alert('Gagal menambahkan data buku tamu');
            }
        }
    });
});

});
</script>

</body>
</html>
