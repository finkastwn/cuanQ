<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'produk'; ?>
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
            <a href="/pembelian-bahan/create" class="create-btn">Tambah Pembelian Bahan</a>
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
                            <?php foreach ($pembelianBahan as $index => $item): ?>
                                <tr>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
        </div>
    </div>
    <?php include(APPPATH . 'Views/partials/snackbar.php'); ?>
</body>
</html>