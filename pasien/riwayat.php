<?php
session_start();

require_once '../includes/config.php';

if (!isset($_SESSION['id_pasien'])) {
  header('Location: login.php');
  exit();
}

$id_pasien = $_SESSION['id_pasien'];

// Ambil data pemeriksaan pasien
$sql = "SELECT dp.id, dp.id_pasien, dp.id_jadwal, dp.keluhan, dp.no_antrian, 
              p.nama AS nama_pasien, jp.hari, jp.jam_mulai, jp.jam_selesai, 
              d.nama AS nama_dokter, poli.nama_poli AS nama_poli, 
              pr.tgl_periksa, pr.catatan, pr.biaya_periksa,
              GROUP_CONCAT(CAST(o.nama_obat AS CHAR) SEPARATOR ', ') AS daftar_obat
        FROM daftar_poli dp
        JOIN pasien p ON dp.id_pasien = p.id
        JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
        JOIN dokter d ON jp.id_dokter = d.id
        JOIN poli ON d.id_poli = poli.id
        LEFT JOIN periksa pr ON dp.id = pr.id_daftar_poli
        LEFT JOIN detail_periksa dpd ON pr.id = dpd.id_periksa
        LEFT JOIN obat o ON dpd.id_obat = o.id
        WHERE dp.id_pasien = :id_pasien
        GROUP BY dp.id
        ORDER BY dp.no_antrian ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_pasien' => $id_pasien]);
$result = $stmt;
?>

<?php include('header.php'); ?>

  <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>

    <!-- Main content -->
<section class="content ml-2">
    <div class="container-fluid">
        <div class="row">
            <h3>Data Riwayat</h3>

            <table id="riwayatTable" class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 10%;">Poli</th>
                        <th style="width: 15%;">Nama Dokter</th>
                        <th style="width: 20%;">Jadwal</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        // Tentukan status berdasarkan apakah ada tanggal periksa
                        $statusText = empty($row['tgl_periksa']) ? "Belum diperiksa" : "Sudah diperiksa";
                        $statusClass = empty($row['tgl_periksa']) ? "badge-danger" : "badge-success";
                        $statusIcon = empty($row['tgl_periksa']) ? "fas fa-times" : "fas fa-check";
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama_poli']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_dokter']); ?></td>
                            <td><?php echo htmlspecialchars($row['hari']) . " jam " . htmlspecialchars($row['jam_mulai']) . " - " . htmlspecialchars($row['jam_selesai']); ?></td>
                            <td>
                                <!-- Menampilkan status dengan <span> dan kelas badge -->
                                <span class="badge <?php echo $statusClass; ?>">
                                    <i class="<?php echo $statusIcon; ?>"></i> <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($row['tgl_periksa'])) { ?>
                                    <button class="btn btn-sm btn-info" 
                                        data-toggle="modal" 
                                        data-target="#riwayatModal"
                                        data-tgl="<?php echo htmlspecialchars($row['tgl_periksa']); ?>"
                                        data-catatan="<?php echo htmlspecialchars($row['catatan']); ?>"
                                        data-biaya="<?php echo number_format($row['biaya_periksa'], 0, ',', '.'); ?>"
                                        data-obat="<?php echo htmlspecialchars($row['daftar_obat']); ?>">
                                        Riwayat
                                    </button>
                                <?php } else { ?>
                                    <button class="btn btn-sm btn-secondary" disabled>Riwayat</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

  <!-- Modal Riwayat Periksa -->
  <div class="modal fade" id="riwayatModal" tabindex="-1" role="dialog" aria-labelledby="riwayatModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="riwayatModalLabel">Detail Riwayat Periksa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Tanggal Periksa:</label>
          <input type="text" class="form-control" id="modalTglPeriksa" readonly>
        </div>
        <div class="form-group">
          <label>Catatan:</label>
          <textarea class="form-control" id="modalCatatan" rows="3" readonly></textarea>
        </div>
        <div class="form-group">
          <label>Obat:</label>
          <textarea class="form-control" id="modalObat" rows="3" readonly></textarea>
        </div>
        <div class="form-group">
          <label>Biaya Periksa:</label>
          <input type="text" class="form-control" id="modalBiaya" readonly>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

  <!-- Footer -->
  <footer class="main-footer">
    <strong>&copy; 2024 Klinik Mitra Waras
  </footer>

  <!-- JS and DataTables Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Script untuk mengambil jadwal berdasarkan dokter yang dipilih -->
  <script>
    if ($.fn.DataTable.isDataTable('#riwayatTable')) {
    $('#riwayatTable').DataTable().destroy();
    }
    $('#riwayatTable').DataTable({
        "paging": true,
        "info": false,
        "searching": false,
        "language": {
            "paginate": {
                "previous": "<i class='fas fa-chevron-left'></i>",
                "next": "<i class='fas fa-chevron-right'></i>"
            }
        }
    });

    $('#riwayatModal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var tglPeriksa = button.data('tgl');
        var catatan = button.data('catatan');
        var biaya = button.data('biaya');
        var obat = button.data('obat');

        // Format tanggal menjadi dd/mm/yyyy dan jam
        if (tglPeriksa) {
            var dateObj = new Date(tglPeriksa); // Asumsikan tglPeriksa adalah dalam format ISO 8601 (yyyy-mm-ddTHH:mm:ss)
            var day = ("0" + dateObj.getDate()).slice(-2); // Menambahkan 0 di depan jika tanggal kurang dari 10
            var month = ("0" + (dateObj.getMonth() + 1)).slice(-2); // Menambahkan 0 di depan jika bulan kurang dari 10
            var year = dateObj.getFullYear();
            var hours = ("0" + dateObj.getHours()).slice(-2);
            var minutes = ("0" + dateObj.getMinutes()).slice(-2);
            var formattedDate = day + "/" + month + "/" + year + " " + hours + ":" + minutes; // Format: dd/mm/yyyy HH:mm

            $('#modalTglPeriksa').val(formattedDate);
        } else {
            $('#modalTglPeriksa').val('Belum diperiksa');
        }

        $('#modalCatatan').val(catatan || 'Belum diperiksa');
        $('#modalBiaya').val('Rp. ' + (biaya || '0'));
        $('#modalObat').val(obat || 'Tidak ada obat');
    });
  </script>

</body>
</html>