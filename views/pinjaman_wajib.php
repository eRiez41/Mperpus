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
    <link rel="stylesheet" href="../css/peminjaman_buku.css">
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
            <h2>Pinjaman Buku Wajib</h2>
        </div>
        <div class="container-fluid mt-4">
            <form action="../fungsi/proses_peminjaman.php" method="POST">
                <div class="mb-3">
                    <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                    <select class="form-select" id="tahun_ajaran" name="tahun_ajaran">
                        <option value="" selected>Semua Tahun Ajaran</option>
                        <?php
                        include '../koneksi.php';
                        $sql = "SELECT DISTINCT tahun_ajaran FROM anggota ORDER BY tahun_ajaran ASC";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['tahun_ajaran'] . "'>" . $row['tahun_ajaran'] . "</option>";
                            }
                        }
                        mysqli_close($conn);
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <select class="form-select" id="kelas" name="kelas">
                        <option value="" selected>Semua Kelas</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="anggota" class="form-label">Anggota</label>
                    <select class="form-select" id="anggota" name="anggota" required>
                        <option selected disabled>Pilih Anggota</option>
                    </select>
                </div>
                <div id="buku-container">
                    <div class="mb-3 buku-group">
                        <label for="buku1" class="form-label">Buku 1</label>
                        <select class="form-select buku-input" id="buku1" name="buku[]" required>
                            <option selected disabled>Pilih Buku</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                    <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required>
                </div>
                <button type="submit" class="btn btn-primary">Pinjam</button>
            </form>
        </div>
    </div>
       
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="../script/sidebar.js"></script>
    <script>
document.getElementById('tahun_ajaran').addEventListener('change', function() {
    var tahunAjaran = this.value;
    var kelasSelect = document.getElementById('kelas');
    kelasSelect.innerHTML = '<option value="" selected>Semua Kelas</option>';
    
    // Fetch kelas based on tahun ajaran
    fetch('../fungsi/get_kelas.php?tahun_ajaran=' + tahunAjaran)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(kelas => {
                    var option = document.createElement('option');
                    option.value = kelas;
                    option.textContent = kelas;
                    kelasSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error fetching kelas:', error));
});

document.getElementById('kelas').addEventListener('change', function() {
    var kelas = this.value;
    var anggotaSelect = document.getElementById('anggota');
    anggotaSelect.innerHTML = '<option selected disabled>Pilih Anggota</option>';
    
    // Fetch anggota based on kelas and tahun ajaran
    var tahunAjaran = document.getElementById('tahun_ajaran').value;
    fetch(`../fungsi/get_anggota.php?kelas=${kelas}&tahun_ajaran=${tahunAjaran}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(anggota => {
                    var option = document.createElement('option');
                    option.value = anggota.id;
                    option.textContent = `${anggota.nama} - ${anggota.nisn}`;
                    anggotaSelect.appendChild(option);
                });
            } else {
                var option = document.createElement('option');
                option.disabled = true;
                option.textContent = 'Tidak ada anggota';
                anggotaSelect.appendChild(option);
            }
        })
        .catch(error => console.error('Error fetching anggota:', error));
});

document.getElementById('kelas').addEventListener('change', function() {
    var kelas = this.value;
    var bukuSelect = document.getElementById('buku1');
    bukuSelect.innerHTML = '<option selected disabled>Pilih Buku</option>';
    
    // Fetch buku based on kelas and tahun ajaran
    var tahunAjaran = document.getElementById('tahun_ajaran').value;
    fetch(`../fungsi/get_buku.php?kelas=${kelas}&tahun_ajaran=${tahunAjaran}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(buku => {
                    var option = document.createElement('option');
                    option.value = buku.kode_buku;
                    option.textContent = buku.judul;
                    bukuSelect.appendChild(option);
                });
            } else {
                var option = document.createElement('option');
                option.disabled = true;
                option.textContent = 'Tidak ada buku';
                bukuSelect.appendChild(option);
            }
        })
        .catch(error => console.error('Error fetching buku:', error));
});

document.getElementById('buku-container').addEventListener('change', function(event) {
    if (event.target.classList.contains('buku-input')) {
        var bukuContainer = document.getElementById('buku-container');
        var bukuGroups = bukuContainer.getElementsByClassName('buku-group');
        var lastGroup = bukuGroups[bukuGroups.length - 1];
        var selectElements = lastGroup.getElementsByTagName('select');

        // Check if the last select element is filled
        if (selectElements.length > 0 && selectElements[0].value !== '' && bukuGroups.length < 10) {
            var newBukuIndex = bukuGroups.length + 1;
            var newBukuDiv = document.createElement('div');
            newBukuDiv.classList.add('mb-3', 'buku-group');
            newBukuDiv.innerHTML = `<label for="buku${newBukuIndex}" class="form-label">Buku ${newBukuIndex}</label>
                                    <select class="form-select buku-input" id="buku${newBukuIndex}" name="buku[]" required>
                                        <option selected disabled>Pilih Buku</option>
                                    </select>`;
            bukuContainer.appendChild(newBukuDiv);

            // Fetch buku based on kelas and tahun ajaran for the new select element
            var kelas = document.getElementById('kelas').value;
            var tahunAjaran = document.getElementById('tahun_ajaran').value;
            fetch(`../fungsi/get_buku.php?kelas=${kelas}&tahun_ajaran=${tahunAjaran}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var newSelect = newBukuDiv.getElementsByTagName('select')[0];
                        data.forEach(buku => {
                            var option = document.createElement('option');
                            option.value = buku.kode_buku;
                            option.textContent = buku.judul;
                            newSelect.appendChild(option);
                        });
                    } else {
                        var option = document.createElement('option');
                        option.disabled = true;
                        option.textContent = 'Tidak ada buku';
                        newSelect.appendChild(option);
                    }
                })
                .catch(error => console.error('Error fetching buku:', error));
        }
    }
});
    </script>

</body>
</html>
