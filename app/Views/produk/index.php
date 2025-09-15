<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'produk'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuanQ - Produk</title>
    <link href="/css/global-font.css" rel="stylesheet">
    <style>
        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 200px;
            text-align: left;
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

    <?php include(APPPATH . 'Views/produk/modal-create.php'); ?>
    <?php include(APPPATH . 'Views/partials/snackbar.php'); ?>
</body>
</html>