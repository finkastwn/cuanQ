<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
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

        return view('dashboard', ['monthly_profit' => $monthly]);
    }
}
