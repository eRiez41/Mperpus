<!-- includes/sidebar.php -->
<div class="sidebar" id="sidebar">
    <div class="admin-section">
        <i class="fas fa-user-circle admin-icon"></i>
        <h4 class="admin-name">library</h4>
    </div>
    <a href="dashboard.php"><i class="fas fa-tachometer-alt text-warning"></i> <span class="link-text">Dashboard</span></a>
    <a href="../views/buku_tamu.php"><i class="fas fa-book-open text"  style="color: lime;"></i> <span class="link-text">Buku Tamu</span></a>
    <div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" id="submenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-book text-primary"></i> <span class="link-text">Buku</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="submenu">
            <a class="dropdown-item" href="daftar_buku.php">Daftar Buku</a>
            <a class="dropdown-item" href="tambah_buku.php">Tambah Buku</a>
        </div>
    </div>
    <div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" id="submenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-users text-success"></i> <span class="link-text">Anggota</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="submenu">
            <a class="dropdown-item" href="daftar_anggota.php">Daftar Anggota</a>
            <a class="dropdown-item" href="tambah_anggota.php">Tambah Anggota</a>
        </div>
    </div>
   

    <a href="../views/peminjaman_buku.php"><i class="fas fa-arrow-right-arrow-left" style="color: violet;"></i> <span class="link-text">Transaksi</span></a>
    <div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" id="submenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-file text-info"></i> <span class="link-text">Laporan</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="submenu">
            <a class="dropdown-item" href="laporan_tamu.php">Laporan Tamu</a>
            <a class="dropdown-item" href="laporan_buku.php">Laporan Buku</a>
            <a class="dropdown-item" href="laporan_peminjaman.php">Laporan Peminjaman</a>
            <a class="dropdown-item" href="laporan_pengembalian.php">Laporan Pengembalian</a>
            <a class="dropdown-item" href="laporan_anggota.php">Laporan Anggota</a>
        </div>
    </div>
    <a href="../views/label.php"><i class="fas fa-tags" style="color: yellow;"></i> <span class="link-text">Label</span></a>
    <a href="../fungsi/logout.php"><i class="fas fa-sign-out-alt text-danger"></i> <span class="link-text">Logout</span></a>
</div>
