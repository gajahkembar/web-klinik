<?php
session_start();
require_once '../includes/config.php';

if (!$pdo) {
    die('Koneksi gagal!');
}

// Cek apakah session id_pasien ada
if (!isset($_SESSION['id_pasien'])) {
    echo "ID Pasien tidak ditemukan.";
    exit; // atau lakukan redirect ke halaman login
}

// Ambil data poli, dokter, dan jadwal
$sql_poli = "SELECT * FROM poli";
$result_poli = $pdo->query($sql_poli)->fetchAll(PDO::FETCH_ASSOC);

$sql_dokter = "SELECT * FROM dokter";
$result_dokter = $pdo->query($sql_dokter)->fetchAll(PDO::FETCH_ASSOC);

$sql_jadwal = "SELECT * FROM jadwal_periksa";
$result_jadwal = $pdo->query($sql_jadwal)->fetchAll(PDO::FETCH_ASSOC);

// Proses pengiriman data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    try {
        // Ambil data dari form
        $id_pasien = $_SESSION['id_pasien']; // Pastikan session id_pasien tersedia
        $id_jadwal = $_POST['id_jadwal'];
        $keluhan = $_POST['keluhan'];

        // Generate nomor antrian
        $sql_antrian = "SELECT COUNT(*) AS total FROM daftar_poli";
        $stmt_antrian = $pdo->prepare($sql_antrian);
        $stmt_antrian->execute();
        $hasil_antrian = $stmt_antrian->fetch(PDO::FETCH_ASSOC);
        $no_antrian = $hasil_antrian['total'] + 1;

        // Masukkan data ke database
        $sql_insert = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian) 
                       VALUES (:id_pasien, :id_jadwal, :keluhan, :no_antrian)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            'id_pasien' => $id_pasien,
            'id_jadwal' => $id_jadwal,
            'keluhan' => $keluhan,
            'no_antrian' => $no_antrian
        ]);

        $_SESSION['success'] = 'Jadwal periksa berhasil ditambahkan!';
    } catch (Exception $e) {
        // SweetAlert gagal
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan: {$e->getMessage()}',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
}
?>

<?php include('header.php'); ?>

<!-- Content -->
<div class="container mt-5">
    <h3>Form Tambah Data Pemeriksaan</h3>
    <form action="" method="POST">
        <div class="form-group">
            <label for="poli">Pilih Poli</label>
            <select class="form-control" id="poli" name="id_poli" required>
                <option value="">Pilih Poli</option>
                <?php foreach ($result_poli as $poli): ?>
                    <option value="<?= $poli['id'] ?>"><?= $poli['nama_poli'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="dokter">Pilih Dokter</label>
            <select class="form-control" id="dokter" name="id_dokter" required>
                <option value="">Pilih Dokter</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jadwal">Pilih Jadwal</label>
            <select class="form-control" id="jadwal" name="id_jadwal" required>
                <option value="">Pilih Jadwal</option>
            </select>
        </div>

        <div class="form-group">
            <label for="keluhan">Keluhan</label>
            <textarea class="form-control" id="keluhan" name="keluhan" rows="3" required></textarea>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- JavaScript -->
<script>
    // Data dari PHP
    const dokterData = <?= json_encode($result_dokter); ?>;
    const jadwalData = <?= json_encode($result_jadwal); ?>;

    document.getElementById('poli').addEventListener('change', function() {
        const poliId = this.value;

        // Filter dokter berdasarkan poli
        const filteredDokter = dokterData.filter(dokter => dokter.id_poli == poliId);

        // Populate dropdown dokter
        const dokterSelect = document.getElementById('dokter');
        dokterSelect.innerHTML = '<option value="">Pilih Dokter</option>';
        filteredDokter.forEach(dokter => {
            const option = document.createElement('option');
            option.value = dokter.id;
            option.textContent = dokter.nama;
            dokterSelect.appendChild(option);
        });

        // Reset jadwal
        const jadwalSelect = document.getElementById('jadwal');
        jadwalSelect.innerHTML = '<option value="">Pilih Jadwal</option>';
    });

    document.getElementById('dokter').addEventListener('change', function() {
        const dokterId = this.value;

        // Filter jadwal berdasarkan dokter
        const filteredJadwal = jadwalData.filter(jadwal => jadwal.id_dokter == dokterId);

        // Populate dropdown jadwal
        const jadwalSelect = document.getElementById('jadwal');
        jadwalSelect.innerHTML = '<option value="">Pilih Jadwal</option>';
        filteredJadwal.forEach(jadwal => {
            const option = document.createElement('option');
            option.value = jadwal.id;
            option.textContent = `${jadwal.hari}, ${jadwal.jam_mulai} - ${jadwal.jam_selesai}`;
            jadwalSelect.appendChild(option);
        });
    });

    <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: '<?= $_SESSION['success'] ?>',
            showConfirmButton: false,
            timer: 1500
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
</script>

</body>
</html>