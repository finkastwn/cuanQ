<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'manual_bahan_usage'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuanQ - Manual Bahan Usage</title>
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-value {
            font-size: 2em;
            font-weight: bold;
            color: <?= MAIN_DARK_COLOR; ?>;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9em;
        }
        
        .purpose-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .purpose-freebie { background-color: #28a745; color: white; }
        .purpose-thank_you_card { background-color: #17a2b8; color: white; }
        .purpose-other { background-color: #6c757d; color: white; }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: center;
        }
        
        .action-buttons td {
            text-align: center;
        }
        
        tr {
            cursor: pointer;
        }
        
        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: #fefefe;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }
        
        .modal-header {
            background: linear-gradient(135deg, <?= MAIN_COLOR; ?>, <?= MAIN_DARK_COLOR; ?>);
            color: white;
            padding: 20px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            margin: 0;
            font-size: 1.4em;
        }
        
        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            background: rgba(255,255,255,0.2);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .close:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }
        
        .modal-body {
            padding: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: <?= MAIN_DARK_COLOR; ?>;
        }
        
        .form-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-input:focus {
            border-color: <?= MAIN_COLOR; ?>;
            outline: none;
        }
        
        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding: 20px 25px;
            border-top: 1px solid #e0e0e0;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, <?= SUCCESS; ?>, #1e7e34);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
    </style>
</head>
<body>
    <?php include(APPPATH . 'Views/partials/sidebar.php'); ?>
    
    <div class="main-content">
        <div class="page-header">
            <div class="title-section">
                <h1 class="page-title">Manual Bahan Usage</h1>
                <h2 class="page-subtitle">Kelola penggunaan bahan baku untuk freebies, thank you card, dll!</h2>
            </div>
            <a href="#" class="create-btn" onclick="openCreateModal()">Tambah Penggunaan</a>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">Rp <?= number_format($total_hpp, 0, ',', '.'); ?></div>
                <div class="stat-label">Total HPP Keseluruhan</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">Rp <?= number_format($total_hpp_freebie, 0, ',', '.'); ?></div>
                <div class="stat-label">HPP Freebies</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">Rp <?= number_format($total_hpp_thank_you, 0, ',', '.'); ?></div>
                <div class="stat-label">HPP Thank You Cards</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">Rp <?= number_format($total_hpp_other, 0, ',', '.'); ?></div>
                <div class="stat-label">HPP Lainnya</div>
            </div>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bahan Baku</th>
                        <th>Jumlah</th>
                        <th>Tujuan</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                        <th>HPP per Unit</th>
                        <th>Total HPP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($manual_usage)): ?>
                        <tr class="no-data-row">
                            <td colspan="9" class="no-data-cell">
                                <div class="no-data">
                                    <div class="no-data-icon">📊</div>
                                    <p>Tidak ada data.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach ($manual_usage as $item): ?>
                            <tr onclick="openEditModal(<?= $item['id'] ?>, '<?= esc($item['description']) ?>', '<?= $item['usage_date'] ?>')">
                                <td><?= $no++; ?></td>
                                <td><?= esc($item['nama_bahan']); ?></td>
                                <td><?= number_format($item['quantity_used']); ?> pcs</td>
                                <td>
                                    <span class="purpose-badge purpose-<?= $item['purpose'] ?>">
                                        <?php
                                        $purposeLabels = [
                                            'freebie' => '🎁 Freebie',
                                            'thank_you_card' => '💌 Thank You',
                                            'other' => '📝 Lainnya'
                                        ];
                                        echo $purposeLabels[$item['purpose']] ?? ucfirst($item['purpose']);
                                        ?>
                                    </span>
                                </td>
                                <td><?= esc($item['description'] ?: '-'); ?></td>
                                <td><?= date('d M Y', strtotime($item['usage_date'])); ?></td>
                                <td>Rp <?= number_format($item['hpp_per_unit'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($item['total_hpp'], 0, ',', '.'); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a onclick="event.stopPropagation(); openEditModal(<?= $item['id'] ?>, '<?= esc($item['description']) ?>', '<?= $item['usage_date'] ?>');" class="btn-edit">✏️ Edit</a>
                                        <a onclick="event.stopPropagation(); deleteUsage(<?= $item['id'] ?>, '<?= esc($item['nama_bahan']) ?>');" class="btn-delete">🗑️ Delete</a>
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
    
    <div id="createModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Tambah Penggunaan Bahan Baku</h2>
                <span class="close" onclick="closeCreateModal()">&times;</span>
            </div>
            
            <div class="modal-body">
                <form id="createForm">
                    <div class="form-group">
                        <label for="bahan_baku_id" class="form-label">Bahan Baku</label>
                        <select id="bahan_baku_id" name="bahan_baku_id" class="form-input" required>
                            <option value="">Pilih Bahan Baku</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity_used" class="form-label">Jumlah Digunakan</label>
                        <input type="number" id="quantity_used" name="quantity_used" class="form-input" min="1" required placeholder="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="purpose" class="form-label">Tujuan Penggunaan</label>
                        <select id="purpose" name="purpose" class="form-input" required>
                            <option value="">Pilih Tujuan</option>
                            <option value="freebie">🎁 Freebie</option>
                            <option value="thank_you_card">💌 Thank You Card</option>
                            <option value="other">📝 Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea id="description" name="description" class="form-input" rows="3" placeholder="Contoh: Freebie untuk pelanggan VIP"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="usage_date" class="form-label">Tanggal Penggunaan</label>
                        <input type="date" id="usage_date" name="usage_date" class="form-input" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div id="availableStockInfo" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px; display: none;">
                        <h5>📦 Stok Tersedia (FIFO):</h5>
                        <div id="stockDetails"></div>
                    </div>
                </form>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeCreateModal()">Batal</button>
                <button type="submit" class="btn-submit" onclick="document.getElementById('createForm').dispatchEvent(new Event('submit'))">Simpan</button>
            </div>
        </div>
    </div>
    
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Penggunaan Bahan Baku</h2>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_usage_id" name="usage_id">
                    
                    <div class="form-group">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea id="edit_description" name="description" class="form-input" rows="3" placeholder="Contoh: Freebie untuk pelanggan VIP"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_usage_date" class="form-label">Tanggal Penggunaan</label>
                        <input type="date" id="edit_usage_date" name="usage_date" class="form-input" required>
                    </div>
                </form>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn-submit" onclick="document.getElementById('editForm').dispatchEvent(new Event('submit'))">Update</button>
            </div>
        </div>
    </div>
    
    <script>
        let availableBahanBaku = [];
        
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
        
        async function openCreateModal() {
            document.getElementById('createModal').style.display = 'flex';
            await loadBahanBakuOptions();
        }
        
        function closeCreateModal() {
            document.getElementById('createModal').style.display = 'none';
            document.getElementById('createForm').reset();
            document.getElementById('availableStockInfo').style.display = 'none';
        }
        
        function openEditModal(id, description, usageDate) {
            document.getElementById('edit_usage_id').value = id;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_usage_date').value = usageDate;
            document.getElementById('editModal').style.display = 'flex';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('editForm').reset();
        }
        
        async function loadBahanBakuOptions() {
            try {
                const response = await fetch('<?= base_url('manual-bahan-usage/get-bahan-baku') ?>');
                const data = await response.json();
                
                const select = document.getElementById('bahan_baku_id');
                select.innerHTML = '<option value="">Pilih Bahan Baku</option>';
                
                availableBahanBaku = data;
                data.forEach(item => {
                    select.innerHTML += `<option value="${item.id}">${item.nama_bahan}</option>`;
                });
            } catch (error) {
                console.error('Error loading bahan baku:', error);
            }
        }
        
        document.getElementById('bahan_baku_id').addEventListener('change', async function() {
            const bahanBakuId = this.value;
            if (!bahanBakuId) {
                document.getElementById('availableStockInfo').style.display = 'none';
                return;
            }
            
            try {
                const response = await fetch(`<?= base_url('manual-bahan-usage/get-available-stock') ?>/${bahanBakuId}`);
                const data = await response.json();
                
                const stockDetails = document.getElementById('stockDetails');
                if (data.length === 0) {
                    stockDetails.innerHTML = '<p style="color: #dc3545;">❌ Tidak ada stok tersedia</p>';
                } else {
                    stockDetails.innerHTML = data.map(batch => `
                        <div style="background: white; border: 1px solid #dee2e6; border-radius: 6px; padding: 10px; margin-bottom: 8px; font-size: 0.9em;">
                            <div style="font-weight: 600; color: <?= MAIN_DARK_COLOR; ?>; margin-bottom: 3px;">${batch.nama_pembelian}</div>
                            <div style="color: #666;">
                                Sisa: ${batch.remaining_stock} pcs | 
                                HPP: Rp ${new Intl.NumberFormat('id-ID').format(batch.hpp_per_unit)} | 
                                Tanggal: ${new Date(batch.tanggal_pembelian).toLocaleDateString('id-ID')}
                            </div>
                        </div>
                    `).join('');
                }
                
                document.getElementById('availableStockInfo').style.display = 'block';
            } catch (error) {
                console.error('Error loading stock info:', error);
            }
        });
        
        document.getElementById('createForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('<?= base_url('manual-bahan-usage/store') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showSnackbar('Penggunaan bahan baku berhasil disimpan!', 'success');
                    closeCreateModal();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showSnackbar('Error: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error saving manual usage:', error);
                showSnackbar('Terjadi kesalahan saat menyimpan data', 'error');
            }
        });
        
        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('<?= base_url('manual-bahan-usage/update') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showSnackbar('Data berhasil diupdate!', 'success');
                    closeEditModal();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showSnackbar('Error: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error updating manual usage:', error);
                showSnackbar('Terjadi kesalahan saat mengupdate data', 'error');
            }
        });
        
        function deleteUsage(id, namaBahan) {
            if (confirm(`Apakah Anda yakin ingin menghapus penggunaan bahan baku "${namaBahan}"?`)) {
                const formData = new FormData();
                formData.append('usage_id', id);
                
                fetch('<?= base_url('manual-bahan-usage/delete') ?>', {
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
                        showSnackbar('Data berhasil dihapus!', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        showSnackbar(data.message || 'Gagal menghapus data', 'error');
                    }
                })
                .catch(() => showSnackbar('Gagal menghapus data', 'error'));
            }
        }
        
        // Close modals when clicking outside
        document.getElementById('createModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateModal();
            }
        });
        
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>
