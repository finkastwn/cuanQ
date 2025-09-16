<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PesananModel;
use App\Models\PesananItemModel;
use App\Models\ProdukModel;
use App\Models\ManualTransactionModel;
use App\Models\PesananBahanUsageModel;

class PesananController extends BaseController
{
    protected $pesananModel;
    protected $pesananItemModel;
    protected $produkModel;
    protected $manualTransactionModel;
    protected $pesananBahanUsageModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->pesananItemModel = new PesananItemModel();
        $this->produkModel = new ProdukModel();
        $this->manualTransactionModel = new ManualTransactionModel();
        $this->pesananBahanUsageModel = new PesananBahanUsageModel();
    }

    public function index()
    {
        $data['pesanan'] = $this->pesananModel
                        ->orderBy('created_at', 'DESC')
                        ->findAll();
        $data['produk'] = $this->produkModel->findAll();
        return view('pesanan/index', $data);
    }
    
    public function detail($pesananId)
    {
        $data['pesanan'] = $this->pesananModel->find($pesananId);
        
        if (!$data['pesanan']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['items'] = $this->pesananItemModel
                              ->where('pesanan_id', $pesananId)
                              ->findAll();
        
        $data['bahan_baku_usage'] = $this->pesananBahanUsageModel->getBahanBakuUsageByPesanan($pesananId);
        
        $data['total_hpp'] = $this->pesananBahanUsageModel->getTotalHppByPesanan($pesananId);
        $data['total_untung'] = $data['pesanan']['total_harga'] - $data['total_hpp'];
        
        return view('pesanan/detail', $data);
    }

    public function store()
    {
        $namaPembeli = $this->request->getPost('nama_pembeli');
        $sourcePenjualan = $this->request->getPost('source_penjualan');
        $tanggalPesanan = $this->request->getPost('tanggal_pesanan');
        $adaBiayaPotongan = $this->request->getPost('ada_biaya_potongan') ? 1 : 0;
        $biayaPemrosesan = $this->request->getPost('biaya_pemrosesan');
        
        if (empty($namaPembeli)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Pembeli Wajib Diisi']);
        }
        
        if (empty($tanggalPesanan)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tanggal Pesanan Wajib Diisi']);
        }
        
        $produkIds = $this->request->getPost('produk_id');
        $jumlahProduk = $this->request->getPost('jumlah_produk');
        $biayaAdminPersen = $this->request->getPost('biaya_admin_persen');
        
        if (empty($produkIds)) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Minimal 1 produk harus diisi.']);
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $subtotal = 0;
            $totalBiayaAdmin = 0;
            $itemsToInsert = [];
            
            foreach ($produkIds as $key => $produkId) {
                $produk = $this->produkModel->find($produkId);
                if (!$produk) continue;
                
                $jumlah = $jumlahProduk[$key];
                $hargaProduk = $produk['harga_final'] ?? $produk['harga_produk'];
                $subtotalItem = $jumlah * $hargaProduk;
                
                $biayaAdminNominal = 0;
                if ($adaBiayaPotongan && isset($biayaAdminPersen[$key])) {
                    $adminPersen = floatval($biayaAdminPersen[$key]);
                    $biayaAdminNominal = ($subtotalItem * $adminPersen) / 100;
                    $totalBiayaAdmin += $biayaAdminNominal;
                }
                
                $subtotal += $subtotalItem;
                
                $itemsToInsert[] = [
                    'produk_id' => $produkId,
                    'nama_produk' => $produk['nama_produk'],
                    'jumlah_produk' => $jumlah,
                    'harga_produk' => $hargaProduk,
                    'biaya_admin_persen' => $adaBiayaPotongan ? ($biayaAdminPersen[$key] ?? 0) : 0,
                    'biaya_admin_nominal' => $biayaAdminNominal,
                    'subtotal_item' => $subtotalItem,
                ];
            }
            
            $totalHarga = $subtotal - $totalBiayaAdmin - ($biayaPemrosesan ?? 0);
            $totalHarga = max(0, $totalHarga);
            
            $dataPesanan = [
                'nama_pembeli' => $namaPembeli,
                'source_penjualan' => $sourcePenjualan,
                'tanggal_pesanan' => $tanggalPesanan,
                'status' => 'pesanan_baru',
                'ada_biaya_potongan' => $adaBiayaPotongan,
                'biaya_pemrosesan' => $biayaPemrosesan ?? 0,
                'subtotal' => $subtotal,
                'total_biaya_admin' => $totalBiayaAdmin,
                'total_harga' => $totalHarga
            ];

            $pesananId = $this->pesananModel->insert($dataPesanan);
            
            if ($pesananId) {
                foreach ($itemsToInsert as &$item) {
                    $item['pesanan_id'] = $pesananId;
                }
                
                $this->pesananItemModel->insertBatch($itemsToInsert);

                $db->transComplete();

                if ($db->transStatus() === false) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menyimpan Data.']);
                }

                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Pesanan Berhasil Disimpan!',
                    'data' => ['pesanan_id' => $pesananId]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menyimpan Pesanan.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update()
    {
        $pesananId = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        
        if (empty($pesananId) || empty($status)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }
        
        $validStatuses = ['pesanan_baru', 'dalam_proses', 'dikirim', 'selesai', 'dicairkan'];
        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Status tidak valid']);
        }
        
        $existingPesanan = $this->pesananModel->find($pesananId);
        if (!$existingPesanan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesanan tidak ditemukan']);
        }
        
        try {
            $oldStatus = $existingPesanan['status'];
            $updated = $this->pesananModel->update($pesananId, ['status' => $status]);
            
            if ($updated) {
                log_message('info', "Pesanan status updated from '{$oldStatus}' to '{$status}' for pesanan ID: {$pesananId}");
                
                try {
                    $this->handleStatusChangeFinancialImpact($pesananId, $oldStatus, $status, $existingPesanan);
                    log_message('info', "Financial impact handled successfully for pesanan ID: {$pesananId}");
                } catch (\Exception $financialError) {
                    log_message('error', "Financial impact handling failed for pesanan ID: {$pesananId}. Error: " . $financialError->getMessage());
                    // Don't fail the status update if financial transaction fails
                    // Just log the error and continue
                }
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Status pesanan berhasil diupdate!'
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengupdate status']);
            }
        } catch (\Exception $e) {
            log_message('error', "Error updating pesanan status: " . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function delete()
    {
        $pesananId = $this->request->getPost('id');
        
        if (empty($pesananId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Pesanan Wajib Diisi']);
        }
        
        $existingPesanan = $this->pesananModel->find($pesananId);
        if (!$existingPesanan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesanan Tidak Ditemukan']);
        }
        
        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $this->pesananItemModel->where('pesanan_id', $pesananId)->delete();
            $deleted = $this->pesananModel->delete($pesananId);
            
            $db->transComplete();
            
            if ($db->transStatus() === false || !$deleted) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menghapus Pesanan']);
            }
            
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Pesanan Berhasil Dihapus!'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    private function handleStatusChangeFinancialImpact($pesananId, $oldStatus, $newStatus, $pesananData)
    {
        log_message('info', "Handling financial impact: pesanan_id={$pesananId}, old_status='{$oldStatus}', new_status='{$newStatus}'");
        
        if ($newStatus === 'selesai' && $oldStatus !== 'selesai') {
            log_message('info', "Creating 'selesai' transaction for pesanan {$pesananId}");
            $this->createFinancialTransaction(
                $pesananData['tanggal_pesanan'],
                'Pesanan Selesai: ' . $pesananData['nama_pembeli'],
                'pemasukan',
                'shopee_pocket',
                $pesananData['total_harga'],
                'pesanan',
                $pesananId
            );
        }
        
        if ($newStatus === 'dicairkan' && $oldStatus !== 'dicairkan') {
            log_message('info', "Creating 'dicairkan' transactions for pesanan {$pesananId}");
            
            if ($oldStatus === 'selesai') {
                log_message('info', "Creating shopee withdrawal transaction for pesanan {$pesananId}");
                $this->createFinancialTransaction(
                    $pesananData['tanggal_pesanan'],
                    'Pencairan dari Shopee: ' . $pesananData['nama_pembeli'],
                    'pengeluaran',
                    'shopee_pocket',
                    $pesananData['total_harga'],
                    'pesanan',
                    $pesananId
                );
            }
            
            log_message('info', "Creating bank deposit transaction for pesanan {$pesananId}");
            $this->createFinancialTransaction(
                $pesananData['tanggal_pesanan'],
                'Pencairan ke Bank: ' . $pesananData['nama_pembeli'],
                'pemasukan',
                'bank_account',
                $pesananData['total_harga'],
                'pesanan',
                $pesananId
            );
        }
        
        if ($oldStatus === 'selesai' && $newStatus !== 'selesai' && $newStatus !== 'dicairkan') {
            $this->removeFinancialTransaction('pesanan', $pesananId, 'shopee_pocket');
        }
        
        if ($oldStatus === 'dicairkan' && $newStatus !== 'dicairkan') {
            $this->removeFinancialTransaction('pesanan', $pesananId, 'bank_account');
            $this->removeFinancialTransaction('pesanan', $pesananId, 'shopee_pocket');
            
            if ($newStatus === 'selesai') {
                $this->createFinancialTransaction(
                    $pesananData['tanggal_pesanan'],
                    'Pesanan Selesai: ' . $pesananData['nama_pembeli'],
                    'pemasukan',
                    'shopee_pocket',
                    $pesananData['total_harga'],
                    'pesanan',
                    $pesananId
                );
            }
        }
    }
    
    private function createFinancialTransaction($tanggal, $keterangan, $type, $sourceMoney, $jumlah, $kategori, $referenceId)
    {
        try {
            $data = [
                'tanggal' => $tanggal,
                'keterangan' => $keterangan,
                'type' => $type,
                'source_money' => $sourceMoney,
                'jumlah' => $jumlah,
                'kategori' => $kategori,
                'reference_id' => $referenceId,
            ];
            
            $result = $this->manualTransactionModel->insert($data);
            if (!$result) {
                log_message('error', 'Failed to create financial transaction: ' . json_encode($data));
                throw new \Exception('Failed to create financial transaction');
            }
            
            log_message('info', 'Financial transaction created successfully: ' . $keterangan);
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error creating financial transaction: ' . $e->getMessage() . ' Data: ' . json_encode($data ?? []));
            throw $e;
        }
    }
    
    private function removeFinancialTransaction($kategori, $referenceId, $sourceMoney = null)
    {
        $builder = $this->manualTransactionModel->builder();
        $builder->where('kategori', $kategori);
        $builder->where('reference_id', $referenceId);
        
        if ($sourceMoney) {
            $builder->where('source_money', $sourceMoney);
        }
        
        $builder->delete();
    }

    public function getBahanBakuUsage($pesananId)
    {
        try {
            $usage = $this->pesananBahanUsageModel->getBahanBakuUsageByPesanan($pesananId);
            return $this->response->setJSON($usage);
        } catch (\Exception $e) {
            return $this->response->setJSON([]);
        }
    }

    public function getAvailableStock($bahanBakuId)
    {
        try {
            $stock = $this->pesananBahanUsageModel->getAvailableStockFIFO($bahanBakuId);
            return $this->response->setJSON($stock);
        } catch (\Exception $e) {
            return $this->response->setJSON([]);
        }
    }

    public function addBahanBakuUsage()
    {
        try {
            $input = json_decode($this->request->getBody(), true);
            log_message('info', 'Raw input received: ' . $this->request->getBody());
            log_message('info', 'Parsed input: ' . json_encode($input));
            
            $pesananId = (int) $input['pesanan_id'];
            $bahanBakuId = (int) $input['bahan_baku_id'];
            $quantityUsed = (int) $input['quantity_used'];

            if (empty($pesananId) || empty($bahanBakuId) || $quantityUsed <= 0) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak lengkap atau tidak valid'
                ]);
            }

            $pesanan = $this->pesananModel->find($pesananId);
            if (!$pesanan) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pesanan tidak ditemukan'
                ]);
            }

            log_message('info', "Attempting to allocate stock: pesanan_id={$pesananId}, bahan_baku_id={$bahanBakuId}, quantity={$quantityUsed}");
            
            $allocations = $this->pesananBahanUsageModel->allocateStockFIFO($pesananId, $bahanBakuId, $quantityUsed);
            
            log_message('info', "Allocations generated: " . json_encode($allocations));

            if (empty($allocations)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak ada stok yang dapat dialokasikan'
                ]);
            }

            $insertedCount = 0;
            foreach ($allocations as $allocation) {
                try {
                    $result = $this->pesananBahanUsageModel->insert($allocation);
                    if ($result) {
                        $insertedCount++;
                        log_message('info', "Successfully inserted allocation with ID {$result}: " . json_encode($allocation));
                    } else {
                        $errors = $this->pesananBahanUsageModel->errors();
                        log_message('error', "Failed to insert allocation: " . json_encode($allocation));
                        log_message('error', "Model validation errors: " . json_encode($errors));
                    }
                } catch (\Exception $insertException) {
                    log_message('error', "Exception during insert: " . $insertException->getMessage());
                    log_message('error', "Allocation data: " . json_encode($allocation));
                }
            }

            log_message('info', "Total allocations inserted: {$insertedCount} out of " . count($allocations));

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Bahan baku berhasil ditambahkan',
                'data' => [
                    'allocations' => $allocations,
                    'inserted_count' => $insertedCount
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error adding bahan baku usage: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteBahanBakuUsage($usageId)
    {
        try {
            $deleted = $this->pesananBahanUsageModel->delete($usageId);
            
            if ($deleted) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Penggunaan bahan baku berhasil dihapus'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting bahan baku usage: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data'
            ]);
        }
    }
}
