<?php $activeMenu = 'dashboard'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CetakinMol - Dashboard</title>
    <link href="/css/global-font.css" rel="stylesheet">
    <style>
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin: 20px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .card h4 { margin: 0 0 6px 0; font-size: 14px; color: #6b7280; font-weight: 600; }
        .card .value { font-size: 20px; font-weight: 700; }
        .value.positive { color: #16a34a; }
        .value.negative { color: #dc2626; }

        .table-wrap { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; margin: 20px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .table-head { display:flex; justify-content: space-between; align-items:center; padding: 12px 16px; background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
        .table-head h2 { margin: 0; font-size: 18px; color: #111827; }
        .nice-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .nice-table thead th { text-align: left; padding: 12px 16px; font-size: 12px; color: #6b7280; letter-spacing: .02em; background: #f9fafb; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; }
        .nice-table thead th.amount { text-align: right; }
        .nice-table tbody td { padding: 12px 16px; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .nice-table tbody tr:nth-child(odd) { background: #fcfcfd; }
        .amount { text-align: right; }
        .amount.positive { color: #16a34a; font-weight: 600; }
        .amount.negative { color: #dc2626; font-weight: 600; }
        .amount.deduction { color: #dc2626; }
        .badge { display:inline-block; padding: 4px 8px; font-size: 12px; border-radius: 999px; background:#eef2ff; color:#3730a3; font-weight:600; }
        .empty { padding: 20px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
<?php include(APPPATH . 'Views/partials/sidebar.php'); ?>
    <div class="main-content">
        <div class="welcome-text">
            <h1>Welcome, <?= session()->get('name') ?>!</h1>
            <p>This is your dashboard.</p>
        </div>

        <?php if (!empty($monthly_profit)): ?>
            <?php 
                $totalPenjualanAll = 0; $totalHppAll = 0; $totalPrintAll = 0; $totalNetAll = 0; 
                foreach ($monthly_profit as $r) { 
                    $totalPenjualanAll += (int)$r['total_harga'];
                    $totalHppAll += (int)$r['total_hpp'];
                    $totalPrintAll += (int)$r['total_print_cost'];
                    $totalNetAll += (int)$r['net_profit'];
                }
                $latest = end($monthly_profit);
            ?>

            <div class="cards-grid">
                <div class="card">
                    <h4>Bulan Terakhir</h4>
                    <div class="value <?= ($latest['net_profit'] >= 0 ? 'positive' : 'negative') ?>">Rp <?= number_format($latest['net_profit'], 0, ',', '.') ?></div>
                    <div style="font-size:12px; color:#6b7280; margin-top:6px;">Periode: <span class="badge"><?= esc($latest['ym']) ?></span></div>
                </div>
                <div class="card">
                    <h4>Total Keuntungan (Semua Periode)</h4>
                    <div class="value <?= ($totalNetAll >= 0 ? 'positive' : 'negative') ?>">Rp <?= number_format($totalNetAll, 0, ',', '.') ?></div>
                </div>
                <div class="card">
                    <h4>Total Penjualan</h4>
                    <div class="value">Rp <?= number_format($totalPenjualanAll, 0, ',', '.') ?></div>
                </div>
                <div class="card">
                    <h4>Total HPP (Bahan + Jasa)</h4>
                    <div class="value negative">Rp <?= number_format($totalHppAll + $totalPrintAll, 0, ',', '.') ?></div>
                </div>
            </div>

            <div class="table-wrap">
                <div class="table-head">
                    <h2>Keuntungan Bersih per Bulan (mulai Nov 2025)</h2>
                </div>
                <div style="overflow:auto;">
                    <table class="nice-table">
                        <colgroup>
                            <col style="width:18%">
                            <col style="width:16%">
                            <col style="width:16%">
                            <col style="width:16%">
                            <col style="width:17%">
                            <col style="width:17%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th class="amount">Total Penjualan</th>
                                <th class="amount">Total HPP Bahan</th>
                                <th class="amount">Total HPP Jasa (Print)</th>
                                <th class="amount">Keuntungan</th>
                                <th class="amount">Pengeluaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($monthly_profit as $row): ?>
                                <tr>
                                    <td><?= esc($row['ym']) ?></td>
                                    <td class="amount">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                    <td class="amount deduction">
                                        Rp <?= number_format($row['hpp_bahan_usage'], 0, ',', '.') ?> / 
                                        Rp <?= number_format($row['hpp_bahan_budget'], 0, ',', '.') ?>
                                    </td>
                                    <td class="amount deduction">
                                        Rp <?= number_format($row['hpp_jasa_usage'], 0, ',', '.') ?> / 
                                        Rp <?= number_format($row['hpp_jasa_budget'], 0, ',', '.') ?>
                                    </td>
                                    <td class="amount <?= ($row['net_profit'] >= 0 ? 'positive' : 'negative') ?>">Rp <?= number_format($row['net_profit'], 0, ',', '.') ?></td>
                                    <td class="amount deduction">Rp <?= number_format($row['expenses'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <div class="table-head">
                    <h2>Keuntungan Bersih per Bulan (mulai Nov 2025)</h2>
                </div>
                <div class="empty">Belum ada data untuk ditampilkan.</div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>