<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'pembelian'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuanQ - Detail Pembelian</title>
    <link href="/css/global-font.css" rel="stylesheet">
    <style>
        .detail-card {
            background-color: <?= WHITE; ?>;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .detail-header {
            border-bottom: 2px solid <?= MAIN_DARK_COLOR; ?>;
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .detail-title {
            font-size: 2em;
            color: <?= MAIN_DARK_COLOR; ?>;
            margin: 0;
        }
        .detail-info {
            font-size: 1.1em;
            color: <?= GRAY; ?>;
        }
        .item-list-container {
            margin-top: 20px;
        }
        .item-card {
            background-color: #f8f9fa;
            border-left: 5px solid <?= VIOLET_ACCENT; ?>;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .item-card-header {
            font-size: 1.2em;
            font-weight: 600;
            color: <?= MAIN_DARK_COLOR; ?>;
            margin-bottom: 10px;
        }
        .item-card-body p {
            margin: 5px 0;
            color: #5a5c69;
        }
        .summary-card {
            background-color: #e3eafc;
            border-left: 5px solid <?= SUCCESS; ?>;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        .summary-card h3 {
            margin-top: 0;
            color: <?= SUCCESS; ?>;
        }
        
        .currency-input-wrapper {
            position: relative;
            display: inline-block;
            width: 200px;
        }
        
        .currency-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-weight: 500;
            z-index: 1;
        }
        
        .currency-input {
            padding-left: 45px !important;
        }
        
        .btn-submit, .btn-cancel {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .btn-submit {
            background-color: #28a745;
            color: white;
        }
        
        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-submit:hover {
            background-color: #218838;
        }
        
        .btn-cancel:hover {
            background-color: #c82333;
        }
        
        .item-edit-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 10px;
        }
        
        .item-edit-fields .form-group {
            margin-bottom: 0;
        }
        
        .item-edit-fields label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #495057;
        }
    </style>
</head>
<body>
    <?php include(APPPATH . 'Views/partials/sidebar.php'); ?>
    
    <div class="main-content">
        <div class="page-header">
            <div class="title-section">
                <h1 class="page-title">Detail Pembelian</h1>
                <h2 class="page-subtitle">Rincian pembelian untuk "<?= esc($pembelian['nama_pembelian']) ?>"</h2>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="/pembelian-bahan" class="create-btn">Kembali ke Daftar</a>
                <button onclick="toggleEditMode()" id="editToggleBtn" class="create-btn">✏️ Edit</button>
            </div>
        </div>
        
        <div class="detail-card">
            <div class="detail-header">
                <div>
                    <h2 class="detail-title" id="display-title"><?= esc($pembelian['nama_pembelian']) ?></h2>
                    <input type="text" id="edit-nama" value="<?= esc($pembelian['nama_pembelian']) ?>" class="form-input" style="display: none; font-size: 2em; font-weight: bold; margin-bottom: 10px;">
                    
                    <div class="detail-info">
                        <span id="display-tanggal">Tanggal: <?= esc(date('Y-m-d', strtotime($pembelian['tanggal_pembelian']))) ?></span>
                        <input type="date" id="edit-tanggal" value="<?= $pembelian['tanggal_pembelian'] ?>" class="form-input" style="display: none;">
                    </div>
                </div>
                <div id="edit-actions" style="display: none;">
                    <button onclick="saveChanges()" class="btn-submit">💾 Simpan</button>
                    <button onclick="cancelEdit()" class="btn-cancel">❌ Batal</button>
                </div>
            </div>

            <div class="item-list-container">
                <h3>Daftar Item</h3>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $index => $item): ?>
                        <div class="item-card" data-item-id="<?= $item['id'] ?>">
                            <div class="item-display" id="item-display-<?= $index ?>">
                                <div class="item-card-header">
                                    <?= esc($item['nama_item']) ?>
                                </div>
                                <div class="item-card-body">
                                    <p>Jumlah: <?= esc($item['jumlah_item']) ?></p>
                                    <p>Isi per Unit: <?= esc($item['isi_per_unit'] ?? 1) ?> pcs</p>
                                    <p>Harga per Pack: Rp <?= number_format($item['harga_item'], 0, ',', '.') ?></p>
                                    <p>Harga per Unit: Rp <?= number_format($item['harga_per_unit'] ?? 0, 0, ',', '.') ?></p>
                                </div>
                            </div>
                            
                            <div class="item-edit" id="item-edit-<?= $index ?>" style="display: none;">
                                <div class="item-card-header">
                                    <select class="form-input item-bahan-select" data-index="<?= $index ?>">
                                        <option value="">Pilih Bahan Baku</option>
                                        <?php if (isset($bahanBaku) && !empty($bahanBaku)): ?>
                                            <?php foreach ($bahanBaku as $bahan): ?>
                                                <option value="<?= $bahan['id'] ?>" <?= ($item['bahan_baku_id'] == $bahan['id']) ? 'selected' : '' ?>>
                                                    <?= esc($bahan['nama_bahan']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="item-card-body">
                                    <div class="item-edit-fields">
                                        <div class="form-group">
                                            <label>Jumlah Beli (Pack/Unit)</label>
                                            <input type="number" class="form-input item-jumlah" data-index="<?= $index ?>" 
                                                   value="<?= $item['jumlah_item'] ?>" min="1">
                                        </div>
                                        <div class="form-group">
                                            <label>Isi per Pack/Unit (pcs)</label>
                                            <input type="number" class="form-input item-isi" data-index="<?= $index ?>" 
                                                   value="<?= $item['isi_per_unit'] ?? 1 ?>" min="1">
                                        </div>
                                        <div class="form-group">
                                            <label>Harga per Pack/Unit</label>
                                            <div class="currency-input-wrapper">
                                                <span class="currency-prefix">Rp</span>
                                                <input type="text" class="form-input currency-input item-harga" data-index="<?= $index ?>" 
                                                       value="<?= number_format($item['harga_item'], 0, ',', '.') ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Harga per Pcs (Auto)</label>
                                            <div class="currency-input-wrapper">
                                                <span class="currency-prefix">Rp</span>
                                                <input type="text" class="form-input item-harga-unit" data-index="<?= $index ?>" 
                                                       value="<?= number_format($item['harga_per_unit'] ?? 0, 0, ',', '.') ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak ada item yang terdaftar untuk pembelian ini.</p>
                <?php endif; ?>
            </div>

            <div class="summary-card">
                <h3>Ringkasan Pembelian</h3>
                <div>
                    <span id="display-admin">Biaya Admin: Rp <?= number_format($pembelian['admin_fee'], 0, ',', '.') ?></span>
                    <div id="edit-admin-container" style="display: none;">
                        <label>Biaya Admin:</label>
                        <div class="currency-input-wrapper">
                            <span class="currency-prefix">Rp</span>
                            <input type="text" id="edit-admin" value="<?= number_format($pembelian['admin_fee'], 0, ',', '.') ?>" class="form-input currency-input">
                        </div>
                    </div>
                </div>
                <div>
                    <span id="display-discount">Diskon: Rp <?= number_format($pembelian['discount'], 0, ',', '.') ?></span>
                    <div id="edit-discount-container" style="display: none;">
                        <label>Diskon:</label>
                        <div class="currency-input-wrapper">
                            <span class="currency-prefix">Rp</span>
                            <input type="text" id="edit-discount" value="<?= number_format($pembelian['discount'], 0, ',', '.') ?>" class="form-input currency-input">
                        </div>
                    </div>
                </div>
                <p>Harga Total: <b>Rp <?= number_format($pembelian['harga_total'], 0, ',', '.') ?></b></p>
            </div>
        </div>
    </div>
    <?php include(APPPATH . 'Views/partials/snackbar.php'); ?>
    
    <script>
        let isEditMode = false;
        const pembelianId = <?= $pembelian['id'] ?>;
        
        function toggleEditMode() {
            isEditMode = !isEditMode;
            
            if (isEditMode) {
                document.getElementById('display-title').style.display = 'none';
                document.getElementById('edit-nama').style.display = 'block';
                
                document.getElementById('display-tanggal').style.display = 'none';
                document.getElementById('edit-tanggal').style.display = 'block';
                
                document.getElementById('display-admin').style.display = 'none';
                document.getElementById('edit-admin-container').style.display = 'block';
                
                document.getElementById('display-discount').style.display = 'none';
                document.getElementById('edit-discount-container').style.display = 'block';
                
                document.querySelectorAll('.item-display').forEach(el => el.style.display = 'none');
                document.querySelectorAll('.item-edit').forEach(el => el.style.display = 'block');
                
                document.getElementById('edit-actions').style.display = 'block';
                document.getElementById('editToggleBtn').textContent = '❌ Batal Edit';
                
                initializeItemEditListeners();
            } else {
                cancelEdit();
            }
        }
        
        function cancelEdit() {
            isEditMode = false;
            
            document.getElementById('display-title').style.display = 'block';
            document.getElementById('edit-nama').style.display = 'none';
            
            document.getElementById('display-tanggal').style.display = 'block';
            document.getElementById('edit-tanggal').style.display = 'none';
            
            document.getElementById('display-admin').style.display = 'block';
            document.getElementById('edit-admin-container').style.display = 'none';
            
            document.getElementById('display-discount').style.display = 'block';
            document.getElementById('edit-discount-container').style.display = 'none';
            
            document.querySelectorAll('.item-display').forEach(el => el.style.display = 'block');
            document.querySelectorAll('.item-edit').forEach(el => el.style.display = 'none');
            
            document.getElementById('edit-actions').style.display = 'none';
            document.getElementById('editToggleBtn').textContent = '✏️ Edit';
            
            document.getElementById('edit-nama').value = '<?= esc($pembelian['nama_pembelian']) ?>';
            document.getElementById('edit-tanggal').value = '<?= $pembelian['tanggal_pembelian'] ?>';
            document.getElementById('edit-admin').value = '<?= number_format($pembelian['admin_fee'], 0, ',', '.') ?>';
            document.getElementById('edit-discount').value = '<?= number_format($pembelian['discount'], 0, ',', '.') ?>';
        }
        
        function initializeItemEditListeners() {
            document.querySelectorAll('.item-harga').forEach(input => {
                input.addEventListener('input', formatCurrency);
                input.addEventListener('input', function() {
                    calculateItemUnitPrice(input.dataset.index);
                });
            });
            
            document.querySelectorAll('.item-isi').forEach(input => {
                input.addEventListener('input', function() {
                    calculateItemUnitPrice(input.dataset.index);
                });
            });
        }
        
        function calculateItemUnitPrice(index) {
            const hargaInput = document.querySelector(`.item-harga[data-index="${index}"]`);
            const isiInput = document.querySelector(`.item-isi[data-index="${index}"]`);
            const unitPriceInput = document.querySelector(`.item-harga-unit[data-index="${index}"]`);
            
            const harga = parseInt(hargaInput.value.replace(/\D/g, '')) || 0;
            const isi = parseInt(isiInput.value) || 1;
            
            const unitPrice = harga / isi;
            unitPriceInput.value = Math.round(unitPrice).toLocaleString('id-ID');
        }
        
        function saveChanges() {
            const formData = new FormData();
            formData.append('pembelian_id', pembelianId);
            formData.append('nama_pembelian', document.getElementById('edit-nama').value);
            formData.append('tanggal_pembelian', document.getElementById('edit-tanggal').value);
            formData.append('admin_fee', document.getElementById('edit-admin').value.replace(/\D/g, ''));
            formData.append('discount', document.getElementById('edit-discount').value.replace(/\D/g, ''));
            
            const items = [];
            document.querySelectorAll('.item-card').forEach((card, index) => {
                const itemId = card.dataset.itemId;
                const bahanBakuId = card.querySelector('.item-bahan-select').value;
                const jumlah = card.querySelector('.item-jumlah').value;
                const isi = card.querySelector('.item-isi').value;
                const harga = card.querySelector('.item-harga').value.replace(/\D/g, '');
                const hargaUnit = card.querySelector('.item-harga-unit').value.replace(/\D/g, '');
                
                items.push({
                    id: itemId,
                    bahan_baku_id: bahanBakuId,
                    jumlah_item: jumlah,
                    isi_per_unit: isi,
                    harga_item: harga,
                    harga_per_unit: hargaUnit
                });
            });
            
            formData.append('items', JSON.stringify(items));
            
            fetch('<?= base_url('pembelian-bahan/update') ?>', {
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
                    showSnackbar('Pembelian Bahan Berhasil Diupdate!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showSnackbar(data.message || 'Gagal Mengupdate Pembelian Bahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSnackbar('Gagal Mengupdate Pembelian Bahan', 'error');
            });
        }
        
        function formatCurrency(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
            }
            e.target.value = value;
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
        
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('edit-admin').addEventListener('input', formatCurrency);
            document.getElementById('edit-discount').addEventListener('input', formatCurrency);
        });
    </script>
</body>
</html>