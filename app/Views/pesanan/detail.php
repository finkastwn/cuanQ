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
            
            <div style="margin-top: 30px;">
                <a href="<?= base_url('pesanan') ?>" class="back-btn">← Kembali ke Daftar Pesanan</a>
            </div>
        </div>
    </div>
</body>
</html>
