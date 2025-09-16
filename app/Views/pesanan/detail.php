<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'pesanan'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuanQ - Detail Pesanan</title>
    <link href="/css/global-font.css" rel="stylesheet">
    <style>
        .detail-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .detail-header {
            background: linear-gradient(135deg, <?= MAIN_COLOR; ?>, <?= MAIN_DARK_COLOR; ?>);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .detail-title {
            font-size: 2.2em;
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        
        .detail-subtitle {
            font-size: 1.1em;
            opacity: 0.9;
            margin: 0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .info-label {
            font-weight: 600;
            color: <?= MAIN_DARK_COLOR; ?>;
            margin-bottom: 8px;
        }
        
        .info-value {
            font-size: 1.1em;
            color: #333;
        }
        
        .source-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.9em;
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
        
        .items-section {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .items-header {
            background-color: <?= MAIN_COLOR; ?>;
            color: white;
            padding: 20px;
            font-size: 1.3em;
            font-weight: 600;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: <?= MAIN_DARK_COLOR; ?>;
            border-bottom: 2px solid #dee2e6;
        }
        
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .items-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .promo-badge {
            background-color: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 600;
            margin-left: 8px;
        }
        
        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9em;
            margin-left: 8px;
        }
        
        .summary-section {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .summary-row:last-child {
            border-bottom: none;
            font-size: 1.2em;
            font-weight: bold;
            color: <?= MAIN_DARK_COLOR; ?>;
            border-top: 2px solid <?= MAIN_COLOR; ?>;
            padding-top: 15px;
            margin-top: 10px;
        }
        
        .back-btn {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include(APPPATH . 'Views/partials/sidebar.php'); ?>
    
    <div class="main-content">
        <div class="detail-container">
            <div class="detail-header">
                <h1 class="detail-title">Detail Pesanan</h1>
                <p class="detail-subtitle">Pesanan dari <?= esc($pesanan['nama_pembeli']) ?></p>
            </div>
            
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">Nama Pembeli</div>
                    <div class="info-value"><?= esc($pesanan['nama_pembeli']) ?></div>
                </div>
                
                <div class="info-card">
                    <div class="info-label">Source Penjualan</div>
                    <div class="info-value">
                        <span class="source-badge source-<?= $pesanan['source_penjualan'] ?>">
                            <?= ucfirst($pesanan['source_penjualan']) ?>
                        </span>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-label">Tanggal Pesanan</div>
                    <div class="info-value"><?= date('d M Y', strtotime($pesanan['tanggal_pesanan'])) ?></div>
                </div>
                
                <div class="info-card">
                    <div class="info-label">Total Harga</div>
                    <div class="info-value" style="font-size: 1.3em; font-weight: bold; color: <?= SUCCESS; ?>;">
                        Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?>
                    </div>
                </div>
            </div>
            
            <div class="items-section">
                <div class="items-header">
                    📦 Produk yang Dipesan
                </div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Biaya Admin</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?= esc($item['nama_produk']) ?>
                                </td>
                                <td><?= number_format($item['jumlah_produk']) ?></td>
                                <td>
                                    Rp <?= number_format($item['harga_produk'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php if ($item['biaya_admin_persen'] > 0): ?>
                                        <?= $item['biaya_admin_persen'] ?>% 
                                        (Rp <?= number_format($item['biaya_admin_nominal'], 0, ',', '.') ?>)
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>Rp <?= number_format($item['subtotal_item'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (!empty($bahan_baku_usage)): ?>
            <div class="items-section">
                <div class="items-header">
                    🧾 Bahan Baku yang Digunakan
                </div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Bahan Baku</th>
                            <th>Jumlah Digunakan</th>
                            <th>Sumber Pembelian</th>
                            <th>Tanggal Beli</th>
                            <th>HPP per Unit</th>
                            <th>Total HPP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bahan_baku_usage as $usage): ?>
                            <tr>
                                <td><?= esc($usage['nama_bahan']) ?></td>
                                <td><?= number_format($usage['quantity_used']) ?> pcs</td>
                                <td><?= esc($usage['nama_pembelian']) ?></td>
                                <td><?= date('d M Y', strtotime($usage['tanggal_pembelian'])) ?></td>
                                <td>Rp <?= number_format($usage['hpp_per_unit'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($usage['total_hpp'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <div class="summary-section">
                <h3 style="margin-top: 0; color: <?= MAIN_DARK_COLOR; ?>;">💰 Ringkasan Pembayaran</h3>
                
                <div class="summary-row">
                    <span>Subtotal Produk:</span>
                    <span>Rp <?= number_format($pesanan['subtotal'], 0, ',', '.') ?></span>
                </div>
                
                <?php if ($pesanan['ada_biaya_potongan']): ?>
                    <div class="summary-row">
                        <span>Total Biaya Admin:</span>
                        <span style="color: #dc3545;">- Rp <?= number_format($pesanan['total_biaya_admin'], 0, ',', '.') ?></span>
                    </div>
                    
                    <?php if ($pesanan['biaya_pemrosesan'] > 0): ?>
                        <div class="summary-row">
                            <span>Biaya Pemrosesan:</span>
                            <span style="color: #dc3545;">- Rp <?= number_format($pesanan['biaya_pemrosesan'], 0, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="summary-row">
                    <span>Total Harga:</span>
                    <span>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></span>
                </div>
            </div>
            
            <?php if (!empty($bahan_baku_usage)): ?>
            <div class="summary-section" style="margin-top: 20px;">
                <h3 style="margin-top: 0; color: <?= MAIN_DARK_COLOR; ?>;">📊 Analisis Keuntungan</h3>
                
                <div class="summary-row">
                    <span>Total Penjualan:</span>
                    <span style="color: <?= SUCCESS; ?>;">Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></span>
                </div>
                
                <div class="summary-row">
                    <span>Total HPP Bahan Baku:</span>
                    <span style="color: #dc3545;">Rp <?= number_format($total_hpp, 0, ',', '.') ?></span>
                </div>
                
                <div class="summary-row">
                    <span>Keuntungan Bersih:</span>
                    <span style="color: <?= $total_untung > 0 ? SUCCESS : '#dc3545' ?>; font-size: 1.3em;">
                        Rp <?= number_format($total_untung, 0, ',', '.') ?>
                        <?php if ($total_hpp > 0): ?>
                            (<?= number_format(($total_untung / $pesanan['total_harga']) * 100, 1) ?>%)
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <?php else: ?>
            <div class="summary-section" style="margin-top: 20px; text-align: center; padding: 40px;">
                <p style="color: #6c757d; font-style: italic; margin: 0;">
                    ℹ️ Belum ada data penggunaan bahan baku untuk pesanan ini.<br>
                    <small>Tambahkan data bahan baku untuk melihat analisis keuntungan.</small>
                </p>
            </div>
            <?php endif; ?>
            
            <div style="margin-top: 30px; display: flex; gap: 15px; align-items: center;">
                <a href="<?= base_url('pesanan') ?>" class="back-btn">← Kembali ke Daftar Pesanan</a>
                <button onclick="openBahanBakuModal()" class="back-btn" style="background: linear-gradient(135deg, <?= SUCCESS; ?>, #1e7e34); border: none; cursor: pointer;">
                    🧾 Kelola Bahan Baku
                </button>
            </div>
        </div>
    </div>
    <div id="snackbar"></div>           
    <div id="bahanBakuModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 900px; max-height: 80vh; overflow-y: auto;">
            <div class="modal-header">
                <h2>🧾 Kelola Bahan Baku - <?= esc($pesanan['nama_pembeli']) ?></h2>
                <span class="close" onclick="closeBahanBakuModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 20px;">
                    <h4 style="color: <?= MAIN_DARK_COLOR; ?>;">Tambah Bahan Baku</h4>
                    <form id="bahanBakuForm">
                        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <label>Bahan Baku:</label>
                                <select id="bahanBakuSelect" class="form-input" required>
                                    <option value="">Pilih Bahan Baku</option>
                                </select>
                            </div>
                            <div>
                                <label>Jumlah Digunakan:</label>
                                <input type="number" id="quantityUsed" class="form-input" min="1" required placeholder="0">
                            </div>
                            <div style="display: flex; align-items: end;">
                                <button type="submit" class="btn-save" style="width: 100%;">Tambah</button>
                            </div>
                        </div>
                    </form>
                    
                    <div id="availableStockInfo" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px; display: none;">
                        <h5>📦 Stok Tersedia (FIFO):</h5>
                        <div id="stockDetails"></div>
                    </div>
                </div>

                <div>
                    <h4 style="color: <?= MAIN_DARK_COLOR; ?>;">Bahan Baku yang Sudah Digunakan</h4>
                    <div id="currentUsageList">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal {
            position: fixed;
            z-index: 1000;
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
            max-width: 900px;
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
        
        .form-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-input:focus {
            border-color: <?= MAIN_COLOR; ?>;
            outline: none;
        }
        
        .btn-save {
            background: linear-gradient(135deg, <?= SUCCESS; ?>, #1e7e34);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        .usage-item {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .usage-info {
            flex-grow: 1;
        }
        
        .usage-title {
            font-weight: 600;
            color: <?= MAIN_DARK_COLOR; ?>;
            margin-bottom: 5px;
        }
        
        .usage-details {
            font-size: 0.9em;
            color: #666;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        .stock-batch {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 8px;
            font-size: 0.9em;
        }
        
        .stock-batch-header {
            font-weight: 600;
            color: <?= MAIN_DARK_COLOR; ?>;
            margin-bottom: 3px;
        }
        
        .stock-batch-info {
            color: #666;
        }
        
        #snackbar {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 16px;
            position: fixed;
            z-index: 10000;
            left: 50%;
            top: 30px;
            font-size: 16px;
            font-weight: 600;
            transform: translateX(-50%);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        #snackbar.success {
            background: linear-gradient(135deg, <?= SUCCESS; ?>, #1e7e34);
        }

        #snackbar.error {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }
        
        #snackbar.show {
            visibility: visible;
            -webkit-animation: snackbar-fadein 0.5s, snackbar-fadeout 0.5s 2.5s;
            animation: snackbar-fadein 0.5s, snackbar-fadeout 0.5s 2.5s;
        }
        
        @-webkit-keyframes snackbar-fadein {
            from {top: 0; opacity: 0;}
            to {top: 30px; opacity: 1;}
        }
        
        @keyframes snackbar-fadein {
            from {top: 0; opacity: 0;}
            to {top: 30px; opacity: 1;}
        }
        
        @-webkit-keyframes snackbar-fadeout {
            from {top: 30px; opacity: 1;}
            to {top: 0; opacity: 0;}
        }
        
        @keyframes snackbar-fadeout {
            from {top: 30px; opacity: 1;}
            to {top: 0; opacity: 0;}
        }
    </style>

    <script>
        const pesananId = <?= $pesanan['id'] ?>;
        let availableBahanBaku = [];
        
        // Snackbar function
        function showSnackbar(message, type = 'success') {
            const snackbar = document.getElementById('snackbar');
            snackbar.textContent = message;
            snackbar.className = 'show ' + type;
            
            setTimeout(() => {
                snackbar.className = snackbar.className.replace('show', '');
            }, 3000);
        }
        
        async function openBahanBakuModal() {
            document.getElementById('bahanBakuModal').style.display = 'flex';
            await loadBahanBakuOptions();
            await loadCurrentUsage();
        }
        
        function closeBahanBakuModal() {
            document.getElementById('bahanBakuModal').style.display = 'none';
            document.getElementById('availableStockInfo').style.display = 'none';
        }
        
        async function loadBahanBakuOptions() {
            try {
                const response = await fetch('<?= base_url('bahan-baku/get-all') ?>');
                const data = await response.json();
                
                const select = document.getElementById('bahanBakuSelect');
                select.innerHTML = '<option value="">Pilih Bahan Baku</option>';
                
                availableBahanBaku = data;
                data.forEach(item => {
                    select.innerHTML += `<option value="${item.id}">${item.nama_bahan}</option>`;
                });
            } catch (error) {
                console.error('Error loading bahan baku:', error);
            }
        }
        
        async function loadCurrentUsage() {
            try {
                const response = await fetch(`<?= base_url('pesanan/get-bahan-baku-usage') ?>/${pesananId}`);
                const data = await response.json();
                
                const container = document.getElementById('currentUsageList');
                if (data.length === 0) {
                    container.innerHTML = '<p style="text-align: center; color: #666; font-style: italic;">Belum ada bahan baku yang digunakan</p>';
                } else {
                    container.innerHTML = data.map(usage => `
                        <div class="usage-item">
                            <div class="usage-info">
                                <div class="usage-title">${usage.nama_bahan}</div>
                                <div class="usage-details">
                                    ${usage.quantity_used} pcs × Rp ${new Intl.NumberFormat('id-ID').format(usage.hpp_per_unit)} = 
                                    <strong>Rp ${new Intl.NumberFormat('id-ID').format(usage.total_hpp)}</strong><br>
                                    <small>Dari: ${usage.nama_pembelian} (${new Date(usage.tanggal_pembelian).toLocaleDateString('id-ID')})</small>
                                </div>
                            </div>
                            <button class="btn-delete" onclick="deleteUsage(${usage.id})">🗑️ Hapus</button>
                        </div>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading current usage:', error);
            }
        }
        
        document.getElementById('bahanBakuSelect').addEventListener('change', async function() {
            const bahanBakuId = this.value;
            if (!bahanBakuId) {
                document.getElementById('availableStockInfo').style.display = 'none';
                return;
            }
            
            try {
                const response = await fetch(`<?= base_url('pesanan/get-available-stock') ?>/${bahanBakuId}`);
                const data = await response.json();
                
                const stockDetails = document.getElementById('stockDetails');
                if (data.length === 0) {
                    stockDetails.innerHTML = '<p style="color: #dc3545;">❌ Tidak ada stok tersedia</p>';
                } else {
                    stockDetails.innerHTML = data.map(batch => `
                        <div class="stock-batch">
                            <div class="stock-batch-header">${batch.nama_pembelian}</div>
                            <div class="stock-batch-info">
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
        
        document.getElementById('bahanBakuForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const bahanBakuId = document.getElementById('bahanBakuSelect').value;
            const quantityUsed = document.getElementById('quantityUsed').value;
            
            if (!bahanBakuId || !quantityUsed) {
                alert('Mohon lengkapi semua field');
                return;
            }
            
            try {
                const response = await fetch('<?= base_url('pesanan/add-bahan-baku-usage') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        pesanan_id: pesananId,
                        bahan_baku_id: bahanBakuId,
                        quantity_used: quantityUsed
                    })
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showSnackbar('Bahan baku berhasil ditambahkan!', 'success');
                    document.getElementById('bahanBakuForm').reset();
                    document.getElementById('availableStockInfo').style.display = 'none';
                    await loadCurrentUsage();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showSnackbar('Error: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error adding bahan baku usage:', error);
                showSnackbar('Terjadi kesalahan saat menambahkan bahan baku', 'error');
            }
        });
        
        async function deleteUsage(usageId) {
            if (!confirm('Yakin ingin menghapus penggunaan bahan baku ini?')) {
                return;
            }
            
            try {
                const response = await fetch(`<?= base_url('pesanan/delete-bahan-baku-usage') ?>/${usageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showSnackbar('Penggunaan bahan baku berhasil dihapus!', 'success');
                    await loadCurrentUsage();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showSnackbar('Error: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error deleting usage:', error);
                showSnackbar('Terjadi kesalahan saat menghapus data', 'error');
            }
        }
        
        document.getElementById('bahanBakuModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBahanBakuModal();
            }
        });
    </script>
</body>
</html>
