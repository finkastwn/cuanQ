<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ManualTransactionModel;
use App\Models\FinancialSummaryModel;
use App\Models\PesananModel;
use App\Models\PembelianBahanModel;

class KeuanganController extends BaseController
{
    protected $manualTransactionModel;
    protected $financialSummaryModel;
    protected $pesananModel;
    protected $pembelianBahanModel;

    public function __construct()
    {
        $this->manualTransactionModel = new ManualTransactionModel();
        $this->financialSummaryModel = new FinancialSummaryModel();
        $this->pesananModel = new PesananModel();
        $this->pembelianBahanModel = new PembelianBahanModel();
    }

    public function index()
    {
        $data['financial_summary'] = $this->calculateFinancialSummary();
        $data['transactions'] = $this->getAllTransactions();
        $data['pesanan_selesai'] = $this->pesananModel->where('status', 'selesai')->findAll();
        $data['pesanan_dicairkan'] = $this->pesananModel->where('status', 'dicairkan')->findAll();
        
        return view('keuangan/index', $data);
    }

    private function calculateFinancialSummary()
    {
        $utangTotal = $this->pembelianBahanModel
                          ->where('source_money', 'duit_pribadi')
                          ->selectSum('harga_total', 'total')
                          ->first()['total'] ?? 0;

        $dicairkanTotal = $this->manualTransactionModel
                              ->where('kategori', 'pesanan')
                              ->where('type', 'pemasukan')
                              ->where('source_money', 'bank_account')
                              ->selectSum('jumlah', 'total')
                              ->first()['total'] ?? 0;

        $pembelianFromBank = $this->pembelianBahanModel
                                 ->where('source_money', 'bank_account')
                                 ->selectSum('harga_total', 'total')
                                 ->first()['total'] ?? 0;

        $manualPemasukanBank = $this->manualTransactionModel
                                   ->where('type', 'pemasukan')
                                   ->where('source_money', 'bank_account')
                                   ->where('kategori', 'manual')
                                   ->selectSum('jumlah', 'total')
                                   ->first()['total'] ?? 0;

        $manualPengeluaranBank = $this->manualTransactionModel
                                     ->where('type', 'pengeluaran')
                                     ->where('source_money', 'bank_account')
                                     ->where('kategori', 'manual')
                                     ->selectSum('jumlah', 'total')
                                     ->first()['total'] ?? 0;

        $manualPengeluaranDuitPribadi = $this->manualTransactionModel
                                           ->where('type', 'pengeluaran')
                                           ->where('source_money', 'duit_pribadi')
                                           ->where('kategori', 'manual')
                                           ->selectSum('jumlah', 'total')
                                           ->first()['total'] ?? 0;

        $utangTotal += $manualPengeluaranDuitPribadi;

        $manualPemasukanShopee = $this->manualTransactionModel
                                    ->where('type', 'pemasukan')
                                    ->where('source_money', 'shopee_pocket')
                                    ->where('kategori', 'manual')
                                    ->selectSum('jumlah', 'total')
                                    ->first()['total'] ?? 0;

        $manualPengeluaranShopee = $this->manualTransactionModel
                                      ->where('type', 'pengeluaran')
                                      ->where('source_money', 'shopee_pocket')
                                      ->where('kategori', 'manual')
                                      ->selectSum('jumlah', 'total')
                                      ->first()['total'] ?? 0;

        $pembelianFromShopee = $this->pembelianBahanModel
                                  ->where('source_money', 'shopee_pocket')
                                  ->selectSum('harga_total', 'total')
                                  ->first()['total'] ?? 0;

        $bankBalance = $dicairkanTotal + $manualPemasukanBank - $pembelianFromBank - $manualPengeluaranBank;

        $shopeePocketFromPesanan = $this->manualTransactionModel
                                      ->where('kategori', 'pesanan')
                                      ->where('type', 'pemasukan')
                                      ->where('source_money', 'shopee_pocket')
                                      ->selectSum('jumlah', 'total')
                                      ->first()['total'] ?? 0;

        $shopeePocketFromPesananOut = $this->manualTransactionModel
                                         ->where('kategori', 'pesanan')
                                         ->where('type', 'pengeluaran')
                                         ->where('source_money', 'shopee_pocket')
                                         ->selectSum('jumlah', 'total')
                                         ->first()['total'] ?? 0;

        $shopeePocketTotal = $shopeePocketFromPesanan - $shopeePocketFromPesananOut + $manualPemasukanShopee - $manualPengeluaranShopee - $pembelianFromShopee;

        return [
            'utang_total' => max(0, $utangTotal),
            'bank_account_balance' => max(0, $bankBalance),
            'shopee_pocket_balance' => max(0, $shopeePocketTotal),
        ];
    }

    private function getAllTransactions()
    {
        $transactions = [];

        $manualTransactions = $this->manualTransactionModel
                                  ->orderBy('tanggal', 'DESC')
                                  ->orderBy('created_at', 'DESC')
                                  ->findAll();

        foreach ($manualTransactions as $transaction) {
            $sourceText = 'Manual Entry';
            $sourceMoney = $transaction['source_money'] ?? 'bank_account';
            switch ($sourceMoney) {
                case 'duit_pribadi':
                    $sourceText = 'Manual Entry (Duit Pribadi)';
                    break;
                case 'bank_account':
                    $sourceText = 'Manual Entry (Bank Account)';
                    break;
                case 'shopee_pocket':
                    $sourceText = 'Manual Entry (Shopee Pocket)';
                    break;
            }

            $transactions[] = [
                'id' => $transaction['id'],
                'tanggal' => $transaction['tanggal'],
                'keterangan' => $transaction['keterangan'],
                'type' => $transaction['type'],
                'source_money' => $sourceMoney,
                'jumlah' => $transaction['jumlah'],
                'kategori' => 'manual',
                'source' => $sourceText,
                'editable' => true,
            ];
        }

        $pembelianBahan = $this->pembelianBahanModel
                              ->orderBy('tanggal_pembelian', 'DESC')
                              ->findAll();

        foreach ($pembelianBahan as $pembelian) {
            $sourceText = '';
            switch ($pembelian['source_money']) {
                case 'duit_pribadi':
                    $sourceText = 'Duit Pribadi (Utang)';
                    break;
                case 'bank_account':
                    $sourceText = 'Bank Account';
                    break;
                case 'shopee_pocket':
                    $sourceText = 'Shopee Pocket';
                    break;
            }

            $transactions[] = [
                'id' => $pembelian['id'],
                'tanggal' => $pembelian['tanggal_pembelian'],
                'keterangan' => 'Pembelian Bahan: ' . $pembelian['nama_pembelian'],
                'type' => 'pengeluaran',
                'jumlah' => $pembelian['harga_total'],
                'kategori' => 'pembelian_bahan',
                'source' => $sourceText,
                'editable' => false,
            ];
        }

        usort($transactions, function($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });

        return $transactions;
    }

    public function store()
    {
        $tanggal = $this->request->getPost('tanggal');
        $keterangan = $this->request->getPost('keterangan');
        $type = $this->request->getPost('type');
        $sourceMoney = $this->request->getPost('source_money');
        $jumlah = $this->request->getPost('jumlah');

        if (empty($tanggal) || empty($keterangan) || empty($type) || empty($sourceMoney) || empty($jumlah)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Semua field wajib diisi']);
        }

        $jumlah = (int) str_replace(['.', ','], '', $jumlah);

        if ($jumlah <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Jumlah harus lebih dari 0']);
        }

        $data = [
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'type' => $type,
            'source_money' => $sourceMoney,
            'jumlah' => $jumlah,
            'kategori' => 'manual',
        ];

        try {
            $inserted = $this->manualTransactionModel->insert($data);

            if ($inserted) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil ditambah!'
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menambah transaksi']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $tanggal = $this->request->getPost('tanggal');
        $keterangan = $this->request->getPost('keterangan');
        $type = $this->request->getPost('type');
        $sourceMoney = $this->request->getPost('source_money');
        $jumlah = $this->request->getPost('jumlah');

        if (empty($id) || empty($tanggal) || empty($keterangan) || empty($type) || empty($sourceMoney) || empty($jumlah)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Semua field wajib diisi']);
        }

        $existing = $this->manualTransactionModel->find($id);
        if (!$existing) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
        }

        $jumlah = (int) str_replace(['.', ','], '', $jumlah);

        if ($jumlah <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Jumlah harus lebih dari 0']);
        }

        $data = [
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'type' => $type,
            'source_money' => $sourceMoney,
            'jumlah' => $jumlah,
        ];

        try {
            $updated = $this->manualTransactionModel->update($id, $data);

            if ($updated) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil diupdate!'
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengupdate transaksi']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID transaksi wajib diisi']);
        }

        $existing = $this->manualTransactionModel->find($id);
        if (!$existing) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
        }

        try {
            $deleted = $this->manualTransactionModel->delete($id);

            if ($deleted) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil dihapus!'
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus transaksi']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function updatePesananStatus()
    {
        $pesananId = $this->request->getPost('pesanan_id');
        $newStatus = $this->request->getPost('status');

        if (empty($pesananId) || empty($newStatus)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        $validStatuses = ['pesanan_baru', 'dalam_proses', 'dikirim', 'selesai', 'dicairkan'];
        if (!in_array($newStatus, $validStatuses)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Status tidak valid']);
        }

        try {
            $updated = $this->pesananModel->update($pesananId, ['status' => $newStatus]);

            if ($updated) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Status pesanan berhasil diupdate!'
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengupdate status']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
