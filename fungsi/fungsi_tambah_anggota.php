<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $nisn = $_POST['nisn'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $kelas = $_POST['kelas'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    $fotoSiswaNewName = null;

    if (!empty($_FILES['foto-siswa']['name'])) {
        // Handle file upload
        $fotoSiswa = $_FILES['foto-siswa'];
        $fotoSiswaName = $fotoSiswa['name'];
        $fotoSiswaTmpName = $fotoSiswa['tmp_name'];
        $fotoSiswaSize = $fotoSiswa['size'];
        $fotoSiswaError = $fotoSiswa['error'];
        $fotoSiswaType = $fotoSiswa['type'];

        $fotoSiswaExt = explode('.', $fotoSiswaName);
        $fotoSiswaActualExt = strtolower(end($fotoSiswaExt));
        $allowed = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fotoSiswaActualExt, $allowed)) {
            if ($fotoSiswaError === 0) {
                $fotoSiswaNewName = uniqid('', true) . "." . $fotoSiswaActualExt;
                $fotoSiswaDestination = '../uploadan/' . $fotoSiswaNewName;

                if ($fotoSiswaSize > 1000000) { // 1MB
                    // Compress the image
                    $image = null;
                    if ($fotoSiswaActualExt == "jpg" || $fotoSiswaActualExt == "jpeg") {
                        $image = imagecreatefromjpeg($fotoSiswaTmpName);
                        imagejpeg($image, $fotoSiswaDestination, 75);
                    } elseif ($fotoSiswaActualExt == "png") {
                        $image = imagecreatefrompng($fotoSiswaTmpName);
                        imagepng($image, $fotoSiswaDestination, 6);
                    } elseif ($fotoSiswaActualExt == "gif") {
                        $image = imagecreatefromgif($fotoSiswaTmpName);
                        imagegif($image, $fotoSiswaDestination);
                    }
                    imagedestroy($image);
                } else {
                    move_uploaded_file($fotoSiswaTmpName, $fotoSiswaDestination);
                }
            } else {
                echo "<script>
                        alert('Terjadi kesalahan saat mengupload file.');
                        window.location.href = '../views/tambah_anggota.php';
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    alert('Jenis file tidak diperbolehkan.');
                    window.location.href = '../views/tambah_anggota.php';
                  </script>";
            exit();
        }
    }

    // Prepare statement
    $sql = "INSERT INTO anggota (nama, nisn, alamat, telepon, kelas, tahun_ajaran, foto_siswa) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt === false) {
        die('MySQL prepare error: ' . mysqli_error($conn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'sssssss', $nama, $nisn, $alamat, $telepon, $kelas, $tahun_ajaran, $fotoSiswaNewName);

    // Execute statement
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('Tambah anggota berhasil');
                window.location.href = '../views/tambah_anggota.php';
              </script>";
    } else {
        echo "<script>
                alert('Terjadi kesalahan saat menambahkan data anggota');
                window.location.href = '../views/tambah_anggota.php';
              </script>";
    }

    // Close statement
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: ../views/tambah_anggota.php");
    exit();
}
?>
