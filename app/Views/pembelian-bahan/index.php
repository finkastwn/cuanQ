<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'pembelian'; ?>
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
        .table th:nth-child(2),
        .table td:nth-child(2) {
            overflow-wrap: break-word; 
            white-space: normal; 
            width: 150px;
        }
        .table tbody tr {
            cursor: pointer;
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
            <a href="#" class="create-btn">Tambah Pembelian Bahan</a>
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
                                <?php $no = 1; ?>
                                <?php foreach ($pembelianBahan as $item): ?>
                                    <tr onclick="window.location.href='<?= base_url('pembelian-bahan/detail/' . $item['id']) ?>'">
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($item['tanggal_pembelian'])); ?></td>
                                        <td><?php echo $item['nama_pembelian']; ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a onclick="event.stopPropagation(); alert('Edit coming soon');" class="btn-edit">✏️ Edit</a>
                                                <a onclick="event.stopPropagation(); alert('Delete coming soon');" class="btn-delete">🗑️ Delete</a>
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
    <?php include(APPPATH . 'Views/pembelian-bahan/modal-create.php'); ?>
</body>
</html>