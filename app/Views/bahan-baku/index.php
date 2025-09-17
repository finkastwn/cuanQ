<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'bahan_baku'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetakin Mol - Bahan Baku</title>
    <link href="/css/global-font.css" rel="stylesheet">
    <style>
        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 120px;
            text-align: center;
            border-left: 1px solid <?= VIOLET_ACCENT; ?>;
            border-radius: 0 10px 0 0;
        }
    </style>
</head>
<body>
    <?php include(APPPATH . 'Views/partials/sidebar.php'); ?>
    
    <div class="main-content">
        <div class="page-header">
            <div class="title-section">
                <h1 class="page-title">Bahan Baku</h1>
                <h2 class="page-subtitle">Kelola bahan bakumu disini!</h2>
            </div>
            <a href="/bahan-baku/create" class="create-btn">Tambah Bahan Baku</a>
        </div>
        
        <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan Baku</th>
                            <th>Harga Bahan /pcs</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bahan_baku)): ?>
                            <tr class="no-data-row">
                                <td colspan="5" class="no-data-cell">
                                    <div class="no-data">
                                        <div class="no-data-icon">📊</div>
                                        <p>Tidak ada data.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bahan_baku as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($item['nama_bahan']) ?></td>
                                    <td>Rp <?= number_format($item['hpp'] ?? 0, 0, ',', '.') ?></td>
                                    <td><?= esc($item['stok'] ?? 0) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a onclick="openEditBahanBakuModal(<?= $item['id'] ?>, '<?= esc($item['nama_bahan']) ?>')" class="btn-edit">✏️ Edit</a>
                                            <a onclick="deleteBahanBaku(<?= $item['id'] ?>, '<?= esc($item['nama_bahan']) ?>')" class="btn-delete">🗑️ Delete</a>
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
    <?php include(APPPATH . 'Views/bahan-baku/modal-create.php'); ?>
    <?php include(APPPATH . 'Views/bahan-baku/modal-edit.php'); ?>
    
    <script>
        function deleteBahanBaku(bahanBakuId, bahanBakuName) {
            if (!confirm(`Apakah Anda yakin ingin menghapus bahan baku "${bahanBakuName}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
                return;
            }
            
            const formData = new FormData();
            formData.append('bahan_baku_id', bahanBakuId);
            
            fetch('<?= base_url('bahan-baku/delete') ?>', {
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
                    showSnackbar('Bahan Baku Berhasil Dihapus!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showSnackbar(data.message || 'Gagal Menghapus Bahan Baku', 'error');
                }
            })
            .catch(() => showSnackbar('Gagal Menghapus Bahan Baku', 'error'));
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
                snackbar.className = snackbar.className.replace("show", "");
            }, 3000);
        }
    </script>
</body>
</html>