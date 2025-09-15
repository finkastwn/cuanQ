<?php $activeMenu = 'produk'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuanQ - Produk</title>
    <link href="/css/global-font.css" rel="stylesheet">
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }
        
        .title-section {
            flex: 1;
        }
        
        .page-title {
            font-size: 2.5em;
            color: <?= MAIN_DARK_COLOR; ?>;
            margin: 0 0 10px 0;
        }
        
        .page-subtitle {
            font-size: 1.1em;
            color: <?= GRAY; ?>;
            margin: 0;
            font-weight: 400;
        }
        
        .create-btn {
            background-color: transparent;
            color: <?= MAIN_DARK_COLOR; ?>;
            border: 2px solid <?= MAIN_DARK_COLOR; ?>;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .create-btn:hover {
            background-color: <?= MAIN_DARK_COLOR; ?>;
            color: <?= WHITE; ?>;
        }
        
        .produk-table {
            width: 100%;
            border-collapse: collapse;
            color: <?= WHITE; ?>;
            table-layout: fixed;
        }
        
        .produk-table th:nth-child(1),
        .produk-table td:nth-child(1) {
            width: 80px;
            text-align: center;
            border-radius: 10px 0 0 0;
            border-right: 1px solid <?= VIOLET_ACCENT; ?>;
        }
        
        .produk-table th:nth-child(2),
        .produk-table td:nth-child(2) {
            width: auto;
        }
        
        .produk-table th:nth-child(4),
        .produk-table td:nth-child(4) {
            width: 200px;
            text-align: left;
            border-left: 1px solid <?= VIOLET_ACCENT; ?>;
            border-radius: 0 10px 0 0;
        }
        
        .produk-table th {
            background-color:  <?= VIOLET_ACCENT; ?>;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .produk-table td {
            background-color:  <?= MAIN_VERY_LIGHT_COLOR; ?>;
            padding: 15px;
            color: <?= MAIN_DARK_COLOR; ?>;
            border-bottom: 1px solid <?= VIOLET_ACCENT; ?>;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            min-width: 60px;
        }
        .btn-edit {
            color: <?= MAIN_DARK_COLOR; ?>;
        }
        .btn-delete {
            color: <?= DANGER; ?>;
        }
        .btn-edit:hover {
            background-color: <?= MAIN_DARK_COLOR; ?>;
            color: <?= WHITE; ?>;
        }
        
        .btn-delete:hover {
            background-color: <?= DANGER_DARK_COLOR; ?>;
            color: <?= WHITE; ?>;
        }
        
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: <?= VIOLET_ACCENT; ?>;
            font-size: 1.2em;
        }
        
        .no-data-icon {
            font-size: 3em;
            margin-bottom: 20px;
            opacity: 0.7;
        }
    
        .produk-table td.no-data-cell {
            border: none !important;
            border-radius: 0 !important;
            background-color: transparent !important;
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
                <table class="produk-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Harga Produk</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($produk)): ?>
                            <tr class="no-data-row">
                                <td colspan="4" class="no-data-cell">
                                    <div class="no-data">
                                        <div class="no-data-icon">📊</div>
                                        <p>Tidak ada data.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($produk as $index => $produk): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($produk['produk']) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/income-categories/edit/<?= $produk['id'] ?>" class="btn-edit">✏️ Edit</a>
                                            <a href="/income-categories/delete/<?= $produk['id'] ?>" class="btn-delete">🗑️ Delete</a>
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
</body>
</html>