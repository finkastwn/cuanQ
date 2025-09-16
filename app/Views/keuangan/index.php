<?php include(APPPATH . 'Views/css/view-with-table.php'); ?>
<?php $activeMenu = 'keuangan'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuanQ - Keuangan</title>
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

        .financial-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-left: 5px solid;
        }

        .summary-card.utang {
            border-left-color: #dc3545;
        }

        .summary-card.bank {
            border-left-color: #28a745;
        }

        .summary-card.shopee {
            border-left-color: #ff6b35;
        }

        .summary-title {
            font-size: 1.1em;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .summary-amount {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-amount.negative {
            color: #dc3545;
        }

        .summary-amount.positive {
            color: #28a745;
        }

        .summary-amount.neutral {
            color: #ff6b35;
        }

        .summary-description {
            font-size: 0.9em;
            color: #666;
        }

        .transactions-section {
            background: white;
            border-radius: 12px;
            padding: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .transactions-header {
            background: linear-gradient(135deg, <?= MAIN_COLOR; ?>, <?= MAIN_DARK_COLOR; ?>);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .transactions-title {
            font-size: 1.4em;
            font-weight: 600;
            margin: 0;
        }

        .transaction-row {
            border-bottom: 1px solid #f0f0f0;
            padding: 15px 20px;
            display: grid;
            grid-template-columns: 100px 1fr 150px 120px 120px 80px;
            gap: 15px;
            align-items: center;
        }

        .transaction-row:hover {
            background-color: #f8f9fa;
        }

        .transaction-date {
            font-size: 0.9em;
            color: #666;
        }

        .transaction-description {
            font-weight: 500;
        }

        .transaction-source {
            font-size: 0.85em;
            color: #666;
        }

        .transaction-type.pemasukan {
            color: #28a745;
            font-weight: 600;
        }

        .transaction-type.pengeluaran {
            color: #dc3545;
            font-weight: 600;
        }

        .transaction-amount {
            font-weight: 600;
            text-align: right;
        }

        .transaction-amount.pemasukan {
            color: #28a745;
        }

        .transaction-amount.pengeluaran {
            color: #dc3545;
        }

        .transaction-actions {
            text-align: center;
        }

        .btn-edit-transaction {
            background: none;
            border: none;
            color: <?= MAIN_COLOR; ?>;
            cursor: pointer;
            font-size: 1.1em;
            padding: 5px;
        }

        .btn-delete-transaction {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 1.1em;
            padding: 5px;
            margin-left: 5px;
        }

        .status-management {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .status-title {
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 15px;
            color: <?= MAIN_DARK_COLOR; ?>;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .status-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
        }

        .status-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 8px;
        }

        .pesanan-info {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <?php include(APPPATH . 'Views/partials/sidebar.php'); ?>
    
    <div class="main-content">
        <div class="page-header">
            <div class="title-section">
                <h1 class="page-title">Keuangan</h1>
                <h2 class="page-subtitle">Kelola keuangan bisnis Anda disini!</h2>
            </div>
            <a href="#" class="create-btn" onclick="openTransactionModal()">Tambah Transaksi</a>
        </div>
        
        <div class="financial-summary">
            <div class="summary-card utang">
                <div class="summary-title">💳 Utang</div>
                <div class="summary-amount negative">Rp <?= number_format($financial_summary['utang_total'], 0, ',', '.') ?></div>
                <div class="summary-description">Dari pembelian dengan duit pribadi</div>
            </div>
            
            <div class="summary-card bank">
                <div class="summary-title">🏦 Bank Account</div>
                <div class="summary-amount positive">Rp <?= number_format($financial_summary['bank_account_balance'], 0, ',', '.') ?></div>
                <div class="summary-description">Saldo di rekening bank</div>
            </div>
            
            <div class="summary-card shopee">
                <div class="summary-title">🛒 Shopee Pocket</div>
                <div class="summary-amount neutral">Rp <?= number_format($financial_summary['shopee_pocket_balance'], 0, ',', '.') ?></div>
                <div class="summary-description">Pesanan selesai belum dicairkan</div>
            </div>
        </div>

        <div class="transactions-section">
            <div class="transactions-header">
                <h3 class="transactions-title">💰 Riwayat Transaksi</h3>
            </div>
            
            <?php if (empty($transactions)): ?>
                <div style="padding: 40px; text-align: center; color: #666;">
                    <div style="font-size: 3em; margin-bottom: 15px;">📊</div>
                    <p>Belum ada transaksi.</p>
                </div>
            <?php else: ?>
                <?php foreach ($transactions as $transaction): ?>
                    <div class="transaction-row">
                        <div class="transaction-date"><?= date('d M Y', strtotime($transaction['tanggal'])) ?></div>
                        <div>
                            <div class="transaction-description"><?= esc($transaction['keterangan']) ?></div>
                            <div class="transaction-source"><?= esc($transaction['source']) ?></div>
                        </div>
                        <div class="transaction-type <?= $transaction['type'] ?>">
                            <?= $transaction['type'] === 'pemasukan' ? '📈 Pemasukan' : '📉 Pengeluaran' ?>
                        </div>
                        <div class="transaction-amount <?= $transaction['type'] ?>">
                            <?= $transaction['type'] === 'pemasukan' ? '+' : '-' ?>Rp <?= number_format($transaction['jumlah'], 0, ',', '.') ?>
                        </div>
                        <div class="transaction-actions">
                            <?php if ($transaction['editable']): ?>
                                <button class="btn-edit-transaction" onclick="editTransaction(<?= $transaction['id'] ?>, '<?= $transaction['tanggal'] ?>', '<?= esc($transaction['keterangan']) ?>', '<?= $transaction['type'] ?>', '<?= $transaction['source_money'] ?? 'bank_account' ?>', <?= $transaction['jumlah'] ?>)">
                                    ✏️
                                </button>
                                <button class="btn-delete-transaction" onclick="deleteTransaction(<?= $transaction['id'] ?>, '<?= esc($transaction['keterangan']) ?>')">
                                    🗑️
                                </button>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include(APPPATH . 'Views/partials/snackbar.php'); ?>
    <?php include(APPPATH . 'Views/keuangan/modal-transaction.php'); ?>
    
    <script>
        function updatePesananStatus(pesananId, newStatus) {
            const formData = new FormData();
            formData.append('pesanan_id', pesananId);
            formData.append('status', newStatus);
            
            fetch('<?= base_url('keuangan/update-pesanan-status') ?>', {
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
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showSnackbar(data.message || 'Gagal mengupdate status', 'error');
                }
            })
            .catch(() => showSnackbar('Gagal mengupdate status', 'error'));
        }

        function deleteTransaction(id, keterangan) {
            if (confirm(`Apakah Anda yakin ingin menghapus transaksi "${keterangan}"?`)) {
                const formData = new FormData();
                formData.append('id', id);
                
                fetch('<?= base_url('keuangan/delete') ?>', {
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
                        showSnackbar('Transaksi berhasil dihapus!', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        showSnackbar(data.message || 'Gagal menghapus transaksi', 'error');
                    }
                })
                .catch(() => showSnackbar('Gagal menghapus transaksi', 'error'));
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
    </script>
</body>
</html>
