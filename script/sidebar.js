 // Fungsi untuk mengambil status sidebar dari penyimpanan lokal
 function getSidebarStatus() {
    return localStorage.getItem('sidebarStatus');
}

// Fungsi untuk menetapkan status sidebar ke penyimpanan lokal
function setSidebarStatus(status) {
    localStorage.setItem('sidebarStatus', status);
}

// Fungsi untuk mengubah status sidebar dan memperbarui tampilan
function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('minimized');
    
    // Periksa apakah sidebar sekarang dalam keadaan minimize atau tidak
    if (sidebar.classList.contains('minimized')) {
        setSidebarStatus('minimized');
    } else {
        setSidebarStatus('maximized');
    }
}

// Fungsi untuk memeriksa status sidebar saat halaman dimuat
window.onload = function() {
    var sidebarStatus = getSidebarStatus();
    if (sidebarStatus === 'minimized') {
        document.getElementById('sidebar').classList.add('minimized');
    }
}