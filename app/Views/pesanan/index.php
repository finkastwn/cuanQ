<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'pesanan'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CetakinMol - Pesanan</title>
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
        
        tr {
            cursor: pointer;
        }
        
        #bulkActions {
            background: linear-gradient(135deg, <?= MAIN_COLOR; ?>, <?= MAIN_DARK_COLOR; ?>);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            align-items: center;
            gap: 15px;
        }
        
        #bulkStatusSelect {
            background: white;
            color: <?= MAIN_DARK_COLOR; ?>;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 6px;
            padding: 8px 12px;
            font-weight: 600;
            min-width: 200px;
        }
        
        #bulkStatusSelect:focus {
            border-color: white;
            outline: none;
            box-shadow: 0 0 0 2px rgba(255,255,255,0.3);
        }
        
        #bulkActions button {
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        #bulkActions .btn-submit {
            background: rgba(255,255,255,0.2);
        }
        
        #bulkActions .btn-submit:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        
        #bulkActions .btn-cancel {
            background: rgba(220,53,69,0.8);
        }
        
        #bulkActions .btn-cancel:hover {
            background: rgba(220,53,69,1);
            transform: translateY(-2px);
        }
        
        .pesanan-checkbox {
            cursor: pointer;
        }
        
        .pesanan-checkbox:checked {
            accent-color: <?= MAIN_COLOR; ?>;
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
            <div style="display: flex; gap: 10px; align-items: center;">
                <div id="bulkActions" style="display: none;">
                    <select id="bulkStatusSelect" class="form-input" style="margin-right: 10px; padding: 8px 12px;">
                        <option value="">Pilih Status</option>
                        <option value="pesanan_baru">🆕 Pesanan Baru</option>
                        <option value="dalam_proses">⏳ Dalam Proses</option>
                        <option value="dikirim">🚚 Dikirim</option>
                        <option value="selesai">✅ Selesai</option>
                        <option value="dicairkan">💰 Dicairkan</option>
                    </select>
                    <button onclick="updateBulkStatus()" class="btn-submit" style="padding: 8px 16px; font-size: 14px;">Update Status</button>
                    <button onclick="clearSelection()" class="btn-cancel" style="padding: 8px 16px; font-size: 14px;">Batal</button>
                </div>
                <a href="#" class="create-btn">Tambah Pesanan</a>
            </div>
        </div>
        
        <div class="search-filter-section" style="margin-bottom: 20px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <form method="GET" action="<?= base_url('pesanan') ?>" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: <?= MAIN_DARK_COLOR; ?>;">🔍 Cari Nama Pembeli</label>
                    <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Masukkan nama pembeli..." 
                           style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px;">
                </div>
                <div style="min-width: 180px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: <?= MAIN_DARK_COLOR; ?>;">📊 Filter Status</label>
                    <select name="status" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px;">
                        <option value="all" <?= ($statusFilter ?? '') === 'all' ? 'selected' : '' ?>>🔄 Semua Status</option>
                        <option value="pesanan_baru" <?= ($statusFilter ?? '') === 'pesanan_baru' ? 'selected' : '' ?>>🆕 Pesanan Baru</option>
                        <option value="dalam_proses" <?= ($statusFilter ?? '') === 'dalam_proses' ? 'selected' : '' ?>>⏳ Dalam Proses</option>
                        <option value="dikirim" <?= ($statusFilter ?? '') === 'dikirim' ? 'selected' : '' ?>>🚚 Dikirim</option>
                        <option value="selesai" <?= ($statusFilter ?? '') === 'selesai' ? 'selected' : '' ?>>✅ Selesai</option>
                        <option value="dicairkan" <?= ($statusFilter ?? '') === 'dicairkan' ? 'selected' : '' ?>>💰 Dicairkan</option>
                    </select>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, <?= MAIN_COLOR; ?>, <?= MAIN_DARK_COLOR; ?>); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                        🔍 Filter
                    </button>
                    <a href="<?= base_url('pesanan') ?>" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block;">
                        🔄 Reset
                    </a>
                </div>
            </form>
        </div>
        
        <?php if (!empty($search) || !empty($statusFilter)): ?>
        <div style="margin-bottom: 15px; padding: 10px 15px; background: #f8f9fa; border-radius: 6px; border-left: 4px solid <?= MAIN_COLOR; ?>;">
            <span style="color: <?= MAIN_DARK_COLOR; ?>; font-weight: 600;">
                📊 Menampilkan <?= count($pesanan) ?> pesanan
                <?php if (!empty($search)): ?>
                    dengan nama pembeli mengandung "<?= esc($search) ?>"
                <?php endif; ?>
                <?php if (!empty($statusFilter) && $statusFilter !== 'all'): ?>
                    dengan status "<?= ucfirst(str_replace('_', ' ', $statusFilter)) ?>"
                <?php endif; ?>
            </span>
        </div>
        <?php endif; ?>
        
        <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" id="selectAll" onchange="toggleAllSelection()" style="transform: scale(1.2);">
                            </th>
                            <th>No</th>
                            <th>Nama Pembeli</th>
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
                                <td colspan="9" class="no-data-cell">
                                    <div class="no-data">
                                        <div class="no-data-icon">📊</div>
                                        <p>Tidak ada data.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                                <?php 
                                    $currentPage = isset($pager) ? ($pager->getCurrentPage('pesanan') ?? 1) : 1;
                                    $perPage = isset($perPage) ? (int)$perPage : 10;
                                    $no = (($currentPage - 1) * $perPage) + 1;
                                ?>
                                <?php foreach ($pesanan as $item): ?>
                                    <tr>
                                        <td onclick="event.stopPropagation();">
                                            <input type="checkbox" class="pesanan-checkbox" value="<?= $item['id'] ?>" onchange="updateBulkActions()" style="transform: scale(1.2);">
                                        </td>
                                        <td onclick="window.open('<?= base_url('pesanan/detail/' . $item['id']) ?>', '_blank')"><?php echo $no++; ?></td>
                                        <td onclick="window.open('<?= base_url('pesanan/detail/' . $item['id']) ?>', '_blank')"><?php echo esc($item['nama_pembeli']); ?></td>
                                        <td onclick="window.open('<?= base_url('pesanan/detail/' . $item['id']) ?>', '_blank')">
                                            <?php 
                                            $status = $item['status'] ?? 'pesanan_baru';
                                            $statusLabels = [
                                                'pesanan_baru' => '🆕',
                                                'dalam_proses' => '⏳', 
                                                'dikirim' => '🚚',
                                                'selesai' => '✅',
                                                'dicairkan' => '💰'
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
                                        <td onclick="window.open('<?= base_url('pesanan/detail/' . $item['id']) ?>', '_blank')">
                                            <?php if (isset($item['bahan_baku_usage_count']) && $item['bahan_baku_usage_count'] > 0): ?>
                                                <span style="color: #28a745; font-weight: 600;">
                                                    ✅
                                                </span>
                                            <?php else: ?>
                                                <span style="color: #dc3545; font-weight: 600;">
                                                    ❌
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td onclick="window.open('<?= base_url('pesanan/detail/' . $item['id']) ?>', '_blank')">Rp <?php echo number_format($item['total_harga'], 0, ',', '.'); ?></td>
                                        <td onclick="window.open('<?= base_url('pesanan/detail/' . $item['id']) ?>', '_blank')"><?php echo date('Y-m-d', strtotime($item['tanggal_pesanan'])); ?></td>
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
                <?php if (isset($pager)): ?>
                <div style="display:flex; justify-content: space-between; align-items:center; padding: 12px 0;">
                    <div>
                        <form method="get" action="<?= base_url('pesanan') ?>" style="display:inline-flex; gap:8px; align-items: baseline;">
                            <?php if (!empty($search)): ?><input type="hidden" name="search" value="<?= esc($search) ?>"><?php endif; ?>
                            <?php if (!empty($statusFilter)): ?><input type="hidden" name="status" value="<?= esc($statusFilter) ?>"><?php endif; ?>
                            <label style="margin:0; font-weight:600; color: <?= MAIN_DARK_COLOR; ?>; display:inline-block;">Per halaman</label>
                            <select name="per_page" onchange="this.form.submit()" class="form-input" style="width:auto; padding:6px 10px;">
                                <?php foreach ([10,20,50,100] as $size): ?>
                                    <option value="<?= $size ?>" <?= ($perPage == $size ? 'selected' : '') ?>><?= $size ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    <div>
                        <style>
                            .pager-wrap { display:flex; justify-content:flex-end; }
                            .pager-list { list-style:none; display:flex; gap:6px; margin:0; padding:0; }
                            .pager-item { }
                            .pager-item.disabled .pager-link { opacity:.4; pointer-events:none; }
                            .pager-item.active .pager-link { background: <?= MAIN_COLOR; ?>; color:#fff; border-color: <?= MAIN_COLOR; ?>; }
                            .pager-link { display:inline-block; min-width:36px; text-align:center; padding:8px 12px; border:1px solid #e0e0e0; border-radius:8px; text-decoration:none; color: <?= MAIN_DARK_COLOR; ?>; font-weight:600; background:#fff; transition:.2s; }
                            .pager-link:hover { border-color: <?= MAIN_COLOR; ?>; box-shadow: 0 2px 8px rgba(0,0,0,.06); transform: translateY(-1px); }
                        </style>
                        <?= $pager->links('pesanan', 'nice_full') ?>
                    </div>
                </div>
                <?php endif; ?>
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
        
        // Bulk operations functions
        function toggleAllSelection() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.pesanan-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            
            updateBulkActions();
        }
        
        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.pesanan-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectAllCheckbox = document.getElementById('selectAll');
            
            if (checkboxes.length > 0) {
                bulkActions.style.display = 'flex';
                document.querySelector('.create-btn').style.display = 'none';
            } else {
                bulkActions.style.display = 'none';
                document.querySelector('.create-btn').style.display = 'block';
                selectAllCheckbox.checked = false;
            }
        }
        
        function clearSelection() {
            const checkboxes = document.querySelectorAll('.pesanan-checkbox');
            const selectAllCheckbox = document.getElementById('selectAll');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            selectAllCheckbox.checked = false;
            
            updateBulkActions();
        }
        
        function updateBulkStatus() {
            const checkboxes = document.querySelectorAll('.pesanan-checkbox:checked');
            const statusSelect = document.getElementById('bulkStatusSelect');
            const selectedStatus = statusSelect.value;
            
            if (checkboxes.length === 0) {
                showSnackbar('Pilih minimal satu pesanan', 'error');
                return;
            }
            
            if (!selectedStatus) {
                showSnackbar('Pilih status yang akan diupdate', 'error');
                return;
            }
            
            const pesananIds = Array.from(checkboxes).map(checkbox => checkbox.value);
            
            if (confirm(`Apakah Anda yakin ingin mengupdate status ${pesananIds.length} pesanan menjadi "${statusSelect.options[statusSelect.selectedIndex].text}"?`)) {
                const formData = new FormData();
                formData.append('pesanan_ids', JSON.stringify(pesananIds));
                formData.append('status', selectedStatus);
                
                fetch('<?= base_url('pesanan/bulk-update-status') ?>', {
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
                        showSnackbar(`Berhasil mengupdate ${data.updated_count} pesanan`, 'success');
                        clearSelection();
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showSnackbar(data.message || 'Gagal mengupdate status', 'error');
                    }
                })
                .catch(() => showSnackbar('Gagal mengupdate status', 'error'));
            }
        }
    </script>
</body>
</html>
