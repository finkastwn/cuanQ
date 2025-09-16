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
            <a href="/pembelian-bahan" class="create-btn">Kembali ke Daftar</a>
        </div>
        
        <div class="detail-card">
            <div class="detail-header">
                <h2 class="detail-title"><?= esc($pembelian['nama_pembelian']) ?></h2>
                <div class="detail-info">
                    Tanggal: <?= esc(date('Y-m-d', strtotime($pembelian['tanggal_pembelian']))) ?><br>
                </div>
            </div>

            <div class="item-list-container">
                <h3>Daftar Item</h3>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <div class="item-card">
                            <div class="item-card-header">
                                <?= esc($item['nama_item']) ?>
                            </div>
                            <div class="item-card-body">
                                <p>Jumlah: <?= esc($item['jumlah_item']) ?></p>
                                <p>Harga: Rp <?= number_format($item['harga_item'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak ada item yang terdaftar untuk pembelian ini.</p>
                <?php endif; ?>
            </div>

            <div class="summary-card">
                <h3>Ringkasan Pembelian</h3>
                <p>Biaya Admin: Rp <?= number_format($pembelian['admin_fee'], 0, ',', '.') ?></p>
                <p>Diskon: Rp <?= number_format($pembelian['discount'], 0, ',', '.') ?></p>
                <p>Harga Total: <b>Rp <?= number_format($pembelian['harga_total'], 0, ',', '.') ?></b></p>
            </div>
        </div>
    </div>
    <?php include(APPPATH . 'Views/partials/snackbar.php'); ?>
</body>
</html>