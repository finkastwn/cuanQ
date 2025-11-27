<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PembelianBahanModel;
use App\Models\ManualTransactionModel;

class Dashboard extends BaseController
{
    protected $pembelianBahanModel;
    protected $manualTransactionModel;

    public function __construct()
    {
        $this->pembelianBahanModel = new PembelianBahanModel();
        $this->manualTransactionModel = new ManualTransactionModel();
    }

    public function index()
    {
        $session = session();
        
        log_message('debug', 'Dashboard accessed. Session data: ' . json_encode($session->get()));
        
        if (!$session->get('isLoggedIn')) {
            log_message('debug', 'User not logged in, redirecting to login page');
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $startDate = '2025-11-01';
        $query = $db->query("
            SELECT 
                DATE_FORMAT(p.tanggal_pesanan, '%Y-%m') as ym,
                SUM(p.total_harga) as total_harga,
                SUM(COALESCE(p.print_cost, 0)) as total_print_cost,
                SUM(COALESCE(h.total_hpp, 0)) as total_hpp
            FROM pesanan p
            LEFT JOIN (
                SELECT pesanan_id, SUM(total_hpp) as total_hpp
                FROM pesanan_bahan_usage
                GROUP BY pesanan_id
            ) h ON h.pesanan_id = p.id
            WHERE p.tanggal_pesanan >= ?
            GROUP BY ym
            ORDER BY ym ASC
        ", [$startDate]);

        $rows = $query->getResultArray();
        $monthly = [];
        foreach ($rows as $r) {
            $net = ((int)$r['total_harga']) - ((int)$r['total_hpp']) - ((int)$r['total_print_cost']);
            $monthly[] = [
                'ym' => $r['ym'],
                'total_harga' => (int)$r['total_harga'],
                'total_hpp' => (int)$r['total_hpp'],
                'total_print_cost' => (int)$r['total_print_cost'],
                'net_profit' => $net,
            ];
        }


        $pembelianBahanMonthly = $db->query("
            SELECT 
                DATE_FORMAT(tanggal_pembelian, '%Y-%m') as ym,
                SUM(harga_total) as total
            FROM pembelian_bahan
            WHERE tanggal_pembelian >= ?
            GROUP BY ym
        ", [$startDate])->getResultArray();

        $allPengeluaranMonthly = $db->query("
            SELECT 
                DATE_FORMAT(tanggal, '%Y-%m') as ym,
                SUM(jumlah) as total
            FROM manual_transactions
            WHERE type = 'pengeluaran' 
                AND tanggal >= ?
            GROUP BY ym
        ", [$startDate])->getResultArray();

        $pembelianBahanByMonth = [];
        foreach ($pembelianBahanMonthly as $pb) {
            $pembelianBahanByMonth[$pb['ym']] = (int)$pb['total'];
        }

        $allPengeluaranByMonth = [];
        foreach ($allPengeluaranMonthly as $ap) {
            $allPengeluaranByMonth[$ap['ym']] = (int)$ap['total'];
        }

        $hppJasaUsageMonthly = $db->query("
            SELECT 
                DATE_FORMAT(tanggal, '%Y-%m') as ym,
                SUM(jumlah) as total
            FROM manual_transactions
            WHERE type = 'pengeluaran' 
                AND budget_source = 'hpp_jasa'
                AND tanggal >= ?
            GROUP BY ym
        ", [$startDate])->getResultArray();

        $hppJasaUsageByMonth = [];
        foreach ($hppJasaUsageMonthly as $hj) {
            $hppJasaUsageByMonth[$hj['ym']] = (int)$hj['total'];
        }

        foreach ($monthly as &$month) {
            $monthExpenses = ($pembelianBahanByMonth[$month['ym']] ?? 0) + ($allPengeluaranByMonth[$month['ym']] ?? 0);
            $month['expenses'] = $monthExpenses;
            $month['net_after_expenses'] = $month['net_profit'] - $monthExpenses;
            
            $month['hpp_bahan_budget'] = $month['total_hpp'];
            $month['hpp_bahan_usage'] = $pembelianBahanByMonth[$month['ym']] ?? 0;
            
            $month['hpp_jasa_budget'] = $month['total_print_cost'];
            $month['hpp_jasa_usage'] = $hppJasaUsageByMonth[$month['ym']] ?? 0;
        }
        unset($month);

        return view('dashboard', [
            'monthly_profit' => $monthly,
        ]);
    }
}
