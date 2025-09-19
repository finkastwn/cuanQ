<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'produk'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CetakinMol - Produk</title>
    <link href="/css/global-font.css" rel="stylesheet">
    <style>
        .table th:nth-child(7),
        .table td:nth-child(7) {
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
                <h1 class="page-title">Produk</h1>
                <h2 class="page-subtitle">Kelola daftar produkmu disini!</h2>
            </div>
            <a href="/produk/create" class="create-btn">Tambah Produk</a>
        </div>
        
        <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Harga Produk</th>
                            <th>Pajak (%)</th>
                            <th>Komisi (%)</th>
                            <th>Status Promo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($produk)): ?>
                            <tr class="no-data-row">
                                <td colspan="7" class="no-data-cell">
                                    <div class="no-data">
                                        <div class="no-data-icon">📊</div>
                                        <p>Tidak ada data.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($produk as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($item['nama_produk'] ?? $item['produk']) ?></td>
                                    <td>Rp <?= number_format($item['harga_produk'] ?? 0, 0, ',', '.') ?></td>
                                    <td><?= ($item['biaya_pajak_persen'] ?? 0) ?>%</td>
                                    <td><?= ($item['komisi_affiliate_persen'] ?? 0) ?>%</td>
                                    <td>
                                        <?php 
                                        $hasPromo = !empty($item['promo_type']) && $item['promo_type'] !== 'none' && $item['promo_active'] == 1;
                                        if ($hasPromo): 
                                        ?>
                                            <span class="promo-active">
                                                🏷️ Aktif<br>
                                                <small>Rp <?= number_format($item['harga_final'] ?? $item['harga_produk'], 0, ',', '.') ?></small>
                                            </span>
                                        <?php else: ?>
                                            <span class="promo-inactive">➖ Tidak Ada Promo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a onclick="openPromoModal(<?= $item['id'] ?>, '<?= esc($item['nama_produk'] ?? $item['produk']) ?>', <?= $item['harga_produk'] ?? 0 ?>)" class="btn-promo">🏷️ Promo</a>
                                            <a onclick="openEditModal(<?= $item['id'] ?>, '<?= esc($item['nama_produk'] ?? $item['produk']) ?>', <?= $item['harga_produk'] ?? 0 ?>, <?= $item['biaya_pajak_persen'] ?? 0 ?>, <?= $item['komisi_affiliate_persen'] ?? 0 ?>)" class="btn-edit">✏️ Edit</a>
                                            <a onclick="deleteProduk(<?= $item['id'] ?>, '<?= esc($item['nama_produk'] ?? $item['produk']) ?>')" class="btn-delete">🗑️ Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
        </div>
    </div>

    <?php include(APPPATH . 'Views/produk/modal-create.php'); ?>
    <?php include(APPPATH . 'Views/produk/modal-edit.php'); ?>
    <?php include(APPPATH . 'Views/produk/modal-promo.php'); ?>
    <?php include(APPPATH . 'Views/partials/snackbar.php'); ?>
    
    <script>
        function deleteProduk(productId, productName) {
            if (!confirm(`Apakah Anda yakin ingin menghapus produk "${productName}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
                return;
            }
            
            const formData = new FormData();
            formData.append('produk_id', productId);
            
            fetch('<?= base_url('produk/delete') ?>', {
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
                    showSnackbar('Produk Berhasil Dihapus!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showSnackbar(data.message || 'Gagal Menghapus Produk', 'error');
                }
            })
            .catch(() => showSnackbar('Gagal Menghapus Produk', 'error'));
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