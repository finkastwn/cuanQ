<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'pembelian'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuanQ - Pembelian Bahan</title>
    <link href="/css/global-font.css" rel="stylesheet">
    <style>
        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 120px;
            text-align: center;
            border-left: 1px solid <?= VIOLET_ACCENT; ?>;
            border-radius: 0 10px 0 0;
        }
        .table th:nth-child(2),
        .table td:nth-child(2) {
            overflow-wrap: break-word; 
            white-space: normal; 
            width: 150px;
        }
        .table tbody tr {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include(APPPATH . 'Views/partials/sidebar.php'); ?>
    
    <div class="main-content">
        <div class="page-header">
            <div class="title-section">
                <h1 class="page-title">Pembelian Bahan</h1>
                <h2 class="page-subtitle">Kelola pembelian bahanmu disini!</h2>
            </div>
            <a href="#" class="create-btn">Tambah Pembelian Bahan</a>
        </div>
        
        <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Pembelian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pembelianBahan)): ?>
                            <tr class="no-data-row">
                                <td colspan="4" class="no-data-cell">
                                    <div class="no-data">
                                        <div class="no-data-icon">📊</div>
                                        <p>Tidak ada data.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php $no = 1; ?>
                                <?php foreach ($pembelianBahan as $item): ?>
                                    <tr onclick="window.location.href='<?= base_url('pembelian-bahan/detail/' . $item['id']) ?>'">
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($item['tanggal_pembelian'])); ?></td>
                                        <td><?php echo $item['nama_pembelian']; ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a onclick="event.stopPropagation(); openEditPembelianModal(<?= $item['id'] ?>, '<?= esc($item['nama_pembelian']) ?>', '<?= $item['tanggal_pembelian'] ?>', <?= $item['admin_fee'] ?? 0 ?>, <?= $item['discount'] ?? 0 ?>);" class="btn-edit">✏️ Edit</a>
                                                <a onclick="event.stopPropagation(); deletePembelianBahan(<?= $item['id'] ?>, '<?= esc($item['nama_pembelian']) ?>');" class="btn-delete">🗑️ Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
        </div>
    </div>
    <?php include(APPPATH . 'Views/partials/snackbar.php'); ?>
    <?php include(APPPATH . 'Views/pembelian-bahan/modal-create.php'); ?>
    <?php include(APPPATH . 'Views/pembelian-bahan/modal-edit.php'); ?>
    
    <script>
        function deletePembelianBahan(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus pembelian "${nama}"?`)) {
                const formData = new FormData();
                formData.append('id', id);
                
                fetch('<?= base_url('pembelian-bahan/delete') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showSnackbar('Pembelian Bahan Berhasil Dihapus!', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        showSnackbar(data.message || 'Gagal Menghapus Pembelian Bahan', 'error');
                    }
                })
                .catch(() => showSnackbar('Gagal Menghapus Pembelian Bahan', 'error'));
            }
        }
        
        function showSnackbar(message, type = 'success') {
            let snackbar = document.getElementById('snackbar');
            
            if (!snackbar) {
                snackbar = document.createElement('div');
                snackbar.id = 'snackbar';
                document.body.appendChild(snackbar);
            }
            
            snackbar.textContent = message;
            snackbar.className = `show ${type}`;
            
            setTimeout(() => {
                snackbar.className = snackbar.className.replace('show', '');
            }, 3000);
        }
    </script>
</body>
</html>