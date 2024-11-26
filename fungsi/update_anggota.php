<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nisn = mysqli_real_escape_string($conn, $_POST['nisn']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $foto_siswa = '';
    $tahun_ajaran = mysqli_real_escape_string($conn, $_POST['tahun_ajaran']);

    // Handle file upload
    if (!empty($_FILES["foto_siswa"]["name"])) {
        $target_dir = "../uploadan/";
        $target_file = $target_dir . basename($_FILES["foto_siswa"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["foto_siswa"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (max 1MB)
        if ($_FILES["foto_siswa"]["size"] > 1000000) {
            $source_image = $_FILES["foto_siswa"]["tmp_name"];
            $compressed_image = $target_dir . 'compressed_' . basename($_FILES["foto_siswa"]["name"]);

            if ($imageFileType == "jpg" || $imageFileType == "jpeg") {
                $image = imagecreatefromjpeg($source_image);
                imagejpeg($image, $compressed_image, 75);
            } elseif ($imageFileType == "png") {
                $image = imagecreatefrompng($source_image);
                imagepng($image, $compressed_image, 6);
            } elseif ($imageFileType == "gif") {
                $image = imagecreatefromgif($source_image);
                imagegif($image, $compressed_image);
            }
            imagedestroy($image);

            $target_file = $compressed_image;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["foto_siswa"]["tmp_name"], $target_file)) {
                $foto_siswa = basename($target_file);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $foto_siswa = $_POST['existing_foto_siswa'];
    }

    // Update anggota details
    $sql = "UPDATE anggota SET nisn='$nisn', nama='$nama', alamat='$alamat', telepon='$telepon', kelas='$kelas', foto_siswa='$foto_siswa', tahun_ajaran='$tahun_ajaran' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Update anggota berhasil');
            window.location.href = '../views/daftar_anggota.php';
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "<script>
        alert('Invalid request method');
        window.location.href = '../views/daftar_anggota.php';
    </script>";
}
?>
