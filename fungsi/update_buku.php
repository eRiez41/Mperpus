<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_buku = mysqli_real_escape_string($conn, $_POST['kode_buku']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $jumlah_lembar = mysqli_real_escape_string($conn, $_POST['jumlah_lembar']);
    $jumlah_buku = mysqli_real_escape_string($conn, $_POST['jumlah_buku']);
    $tahun_masuk = mysqli_real_escape_string($conn, $_POST['tahun_masuk']);
    $harga_buku = mysqli_real_escape_string($conn, $_POST['harga_buku']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $tahun_ajaran = mysqli_real_escape_string($conn, $_POST['tahun_ajaran']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    
    // Handle image upload
    $gambar_buku = null;
    if (!empty($_FILES["gambar_buku"]["name"])) {
        $target_dir = "../uploadan/";
        $target_file = $target_dir . basename($_FILES["gambar_buku"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Check if file is an image
        $check = getimagesize($_FILES["gambar_buku"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (max 1MB)
        if ($_FILES["gambar_buku"]["size"] > 1000000) {
            $source_image = $_FILES["gambar_buku"]["tmp_name"];
            $compressed_image = $target_dir . 'compressed_' . basename($_FILES["gambar_buku"]["name"]);

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
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // If everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["gambar_buku"]["tmp_name"], $target_file)) {
                $gambar_buku = basename($target_file);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update query
    $sql = "UPDATE buku SET 
            judul='$judul', 
            penerbit='$penerbit', 
            tahun_terbit='$tahun_terbit', 
            kategori='$kategori', 
            isbn='$isbn', 
            jumlah_lembar='$jumlah_lembar', 
            jumlah_buku='$jumlah_buku', 
            tahun_masuk='$tahun_masuk', 
            harga_buku='$harga_buku',
            penulis='$penulis',
            tahun_ajaran='$tahun_ajaran',
            kelas='$kelas'
            ";

    // If a new image was uploaded, update the image field
    if ($gambar_buku !== null) {
        $sql .= ", gambar_buku='$gambar_buku'";
    }

    $sql .= " WHERE kode_buku='$kode_buku'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Update buku berhasil');
            window.location.href = '../views/daftar_buku.php';
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Invalid request method.";
}
?>
