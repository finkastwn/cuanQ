<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'pesanan'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuanQ - Pesanan</title>
    <link href="/css/global-font.css" rel="stylesheet">
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .title-section h1 {
            margin: 0 0 5px 0;
            color: <?= MAIN_DARK_COLOR; ?>;
            font-size: 2.5em;
            font-weight: bold;
        }
        
        .title-section h2 {
            margin: 0;
            color: <?= GRAY; ?>;
            font-size: 1.2em;
            font-weight: normal;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: center;
        }
        
        .action-buttons td {
            text-align: center;
        }
        
        .source-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .source-shopee { background-color: #ff6b35; color: white; }
        .source-tiktok { background-color: #000; color: white; }
        .source-facebook { background-color: #1877f2; color: white; }
        .source-twitter { background-color: #1da1f2; color: white; }
        .source-instagram { background-color: #e4405f; color: white; }
        .source-whatsapp { background-color: #25d366; color: white; }
        .source-offline { background-color: #6c757d; color: white; }
        .source-other { background-color: #17a2b8; color: white; }
        
        tr {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include(APPPATH . 'Views/partials/sidebar.php'); ?>
    
    <div class="main-content">
        <div class="page-header">
            <div class="title-section">
                <h1 class="page-title">Pesanan</h1>
                <h2 class="page-subtitle">Kelola pesanan pelangganmu disini!</h2>
            </div>
            <a href="#" class="create-btn">Tambah Pesanan</a>
        </div>
        
        <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pembeli</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Bahan Baku</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($pesanan)): ?>
                            <tr class="no-data-row">
                                <td colspan="8" class="no-data-cell">
                                    <div class="no-data">
                                        <div class="no-data-icon">📊</div>
                                        <p>Tidak ada data.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                                <?php $no = 1; ?>
                                <?php foreach ($pesanan as $item): ?>
                                    <tr onclick="window.open('<?= base_url('pesanan/detail/' . $item['id']) ?>', '_blank')">
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo esc($item['nama_pembeli']); ?></td>
                                        <td>
                                            <span class="source-badge source-<?= $item['source_penjualan'] ?>">
                                                <?= ucfirst($item['source_penjualan']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            $status = $item['status'] ?? 'pesanan_baru';
                                            $statusLabels = [
                                                'pesanan_baru' => '🆕 Baru',
                                                'dalam_proses' => '⏳ Proses', 
                                                'dikirim' => '🚚 Dikirim',
                                                'selesai' => '✅ Selesai',
                                                'dicairkan' => '💰 Dicairkan'
                                            ];
                                            $statusColors = [
                                                'pesanan_baru' => '#6c757d',
                                                'dalam_proses' => '#ffc107', 
                                                'dikirim' => '#17a2b8',
                                                'selesai' => '#28a745',
                                                'dicairkan' => '#007bff'
                                            ];
                                            ?>
                                            <span style="color: <?= $statusColors[$status] ?>; font-weight: 600;">
                                                <?= $statusLabels[$status] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (isset($item['bahan_baku_usage_count']) && $item['bahan_baku_usage_count'] > 0): ?>
                                                <span style="color: #28a745; font-weight: 600;">
                                                    ✅ Sudah (<?= $item['bahan_baku_usage_count'] ?>)
                                                </span>
                                            <?php else: ?>
                                                <span style="color: #dc3545; font-weight: 600;">
                                                    ❌ Belum
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>Rp <?php echo number_format($item['total_harga'], 0, ',', '.'); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($item['tanggal_pesanan'])); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a onclick="event.stopPropagation(); openEditStatusModal(<?= $item['id'] ?>, '<?= esc($item['nama_pembeli']) ?>', '<?= $status ?>');" class="btn-edit">📝 Edit</a>
                                                <a onclick="event.stopPropagation(); deletePesanan(<?= $item['id'] ?>, '<?= esc($item['nama_pembeli']) ?>');" class="btn-delete">🗑️ Delete</a>
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
    <?php include(APPPATH . 'Views/pesanan/modal-create.php'); ?>
    
    <div id="editStatusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Update Status Pesanan</h2>
                <span class="close" onclick="closeEditStatusModal()">&times;</span>
            </div>
            
            <form id="editStatusForm">
                <input type="hidden" id="edit_pesanan_id" name="id">
                
                <div class="form-group">
                    <label class="form-label">Nama Pembeli</label>
                    <input type="text" id="edit_nama_pembeli" class="form-input" readonly>
                </div>
                
                <div class="form-group">
                    <label for="edit_status" class="form-label">Status</label>
                    <select id="edit_status" name="status" class="form-input" required>
                        <option value="pesanan_baru">🆕 Pesanan Baru</option>
                        <option value="dalam_proses">⏳ Dalam Proses</option>
                        <option value="dikirim">🚚 Dikirim</option>
                        <option value="selesai">✅ Selesai</option>
                        <option value="dicairkan">💰 Dicairkan</option>
                    </select>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditStatusModal()">Kembali</button>
                    <button type="submit" class="btn-submit">Update Status</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openEditStatusModal(id, nama, status) {
            document.getElementById('edit_pesanan_id').value = id;
            document.getElementById('edit_nama_pembeli').value = nama;
            document.getElementById('edit_status').value = status;
            document.getElementById('editStatusModal').style.display = 'block';
        }
        
        function closeEditStatusModal() {
            document.getElementById('editStatusModal').style.display = 'none';
            document.getElementById('editStatusForm').reset();
        }
        
        document.getElementById('editStatusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('<?= base_url('pesanan/update') ?>', {
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
                    showSnackbar('Status pesanan berhasil diupdate!', 'success');
                    closeEditStatusModal();
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showSnackbar(data.message || 'Gagal mengupdate status', 'error');
                }
            })
            .catch(() => showSnackbar('Gagal mengupdate status', 'error'));
        });
        
        function deletePesanan(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus pesanan dari "${nama}"?`)) {
                const formData = new FormData();
                formData.append('id', id);
                
                fetch('<?= base_url('pesanan/delete') ?>', {
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
                        showSnackbar('Pesanan Berhasil Dihapus!', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        showSnackbar(data.message || 'Gagal Menghapus Pesanan', 'error');
                    }
                })
                .catch(() => showSnackbar('Gagal Menghapus Pesanan', 'error'));
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
