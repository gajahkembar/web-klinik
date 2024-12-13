<?php
session_start();
include('../includes/config.php');

try {
    // Query untuk mengambil data pasien yang belum diperiksa, termasuk nama dokter dan jadwal dokter
    $query = "SELECT dp.id, dp.no_antrian, p.nama AS nama_pasien, p.no_rm, dp.keluhan, 
                     d.nama AS nama_dokter, 
                     CONCAT(jp.hari, ' ', jp.jam_mulai, '-', jp.jam_selesai) AS jadwal_dokter, 
                     pl.nama_poli, 
                     'Belum Diperiksa' AS status
              FROM daftar_poli dp
              JOIN pasien p ON dp.id_pasien = p.id
              JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
              JOIN dokter d ON jp.id_dokter = d.id
              JOIN poli pl ON d.id_poli = pl.id
              WHERE NOT EXISTS (
                  SELECT 1 FROM periksa WHERE periksa.id_daftar_poli = dp.id
              )
              ORDER BY dp.no_antrian ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<?php include('header.php'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pasien Belum Diperiksa</h1>
                </div>
            </div>
        </div>
    </div>

<!-- Main content -->
<section class="content ml-2">
    <div class="container-fluid">
        <div class="row">
            <div class="ml-auto mr-3"></div>

            <table id="antrianTable" class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 15%">Nama Pasien</th>
                        <th style="width: 15%">No Rekam Medis</th>
                        <th style="width: 15%">Nama Dokter</th>
                        <th style="width: 20%">Jadwal Dokter</th>
                        <th style="width: 20%">Keluhan</th>
                        <th style="width: 10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                                <td><?= htmlspecialchars($row['no_rm']) ?></td>
                                <td><?= htmlspecialchars($row['nama_dokter']) ?></td>
                                <td><?= htmlspecialchars($row['jadwal_dokter']) ?></td>
                                <td><?= htmlspecialchars($row['keluhan']) ?></td>
                                <td>
                                    <span class="badge badge-warning"><?= htmlspecialchars($row['status']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">Tidak ada data pasien yang belum diperiksa.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $('#antrianTable').DataTable({
        paging: true,
        info: true,
        searching: true,
        language: {
            paginate: {
                previous: "<i class='fas fa-chevron-left'></i>",
                next: "<i class='fas fa-chevron-right'></i>"
            }
        }
    });
</script>

</body>
</html>