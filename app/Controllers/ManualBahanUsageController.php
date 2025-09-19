<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ManualBahanUsageModel;
use App\Models\BahanBakuModel;

class ManualBahanUsageController extends BaseController
{
    protected $manualBahanUsageModel;
    protected $bahanBakuModel;

    public function __construct()
    {
        $this->manualBahanUsageModel = new ManualBahanUsageModel();
        $this->bahanBakuModel = new BahanBakuModel();
    }

    public function index()
    {
        $data['manual_usage'] = $this->manualBahanUsageModel->getManualUsageWithDetails();
        $data['bahan_baku'] = $this->bahanBakuModel->findAll();
        $data['total_hpp'] = $this->manualBahanUsageModel->getTotalHppByPurpose();
        $data['total_hpp_freebie'] = $this->manualBahanUsageModel->getTotalHppByPurpose('freebie');
        $data['total_hpp_thank_you'] = $this->manualBahanUsageModel->getTotalHppByPurpose('thank_you_card');
        $data['total_hpp_other'] = $this->manualBahanUsageModel->getTotalHppByPurpose('other');
        
        return view('manual-bahan-usage/index', $data);
    }

    public function store()
    {
        $bahanBakuId = $this->request->getPost('bahan_baku_id');
        $quantityUsed = $this->request->getPost('quantity_used');
        $purpose = $this->request->getPost('purpose');
        $description = $this->request->getPost('description');
        $usageDate = $this->request->getPost('usage_date');
        
        if (empty($bahanBakuId) || empty($quantityUsed) || empty($purpose) || empty($usageDate)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        if ($quantityUsed <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Jumlah harus lebih dari 0']);
        }

        $validPurposes = ['freebie', 'thank_you_card', 'other'];
        if (!in_array($purpose, $validPurposes)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tujuan penggunaan tidak valid']);
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $stockService = new \App\Libraries\StockService();
            $allocations = $stockService->allocateStockForManualUsage(
                $bahanBakuId, 
                $quantityUsed, 
                $purpose, 
                $description, 
                $usageDate
            );
            
            $insertedCount = 0;
            foreach ($allocations as $allocation) {
                $result = $this->manualBahanUsageModel->insert($allocation);
                if ($result) {
                    $insertedCount++;
                    $this->updateBahanBakuStock($allocation['bahan_baku_id'], -$allocation['quantity_used']);
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data']);
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Penggunaan bahan baku berhasil disimpan',
                'data' => [
                    'allocations' => $allocations,
                    'inserted_count' => $insertedCount
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error storing manual bahan usage: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update()
    {
        $usageId = $this->request->getPost('usage_id');
        $description = $this->request->getPost('description');
        $usageDate = $this->request->getPost('usage_date');
        
        if (empty($usageId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID penggunaan harus diisi']);
        }

        $existingUsage = $this->manualBahanUsageModel->find($usageId);
        if (!$existingUsage) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
        
        $data = [];
        if (!empty($description)) {
            $data['description'] = $description;
        }
        if (!empty($usageDate)) {
            $data['usage_date'] = $usageDate;
        }
        
        if (empty($data)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang diupdate']);
        }

        try {
            $result = $this->manualBahanUsageModel->update($usageId, $data);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengupdate data']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating manual bahan usage: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function delete()
    {
        $usageId = $this->request->getPost('usage_id');
        
        if (empty($usageId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID penggunaan harus diisi']);
        }

        $existingUsage = $this->manualBahanUsageModel->find($usageId);
        if (!$existingUsage) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $deleted = $this->manualBahanUsageModel->delete($usageId);
            
            if ($deleted) {
                $this->updateBahanBakuStock($existingUsage['bahan_baku_id'], $existingUsage['quantity_used']);
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false || !$deleted) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data']);
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error deleting manual bahan usage: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function getAvailableStock($bahanBakuId)
    {
        try {
            $stockService = new \App\Libraries\StockService();
            $stock = $stockService->getAvailableStockFIFO($bahanBakuId);
            return $this->response->setJSON($stock);
        } catch (\Exception $e) {
            log_message('error', 'Error getting available stock: ' . $e->getMessage());
            return $this->response->setJSON([]);
        }
    }

    public function getAllBahanBaku()
    {
        try {
            $bahanBaku = $this->bahanBakuModel->findAll();
            return $this->response->setJSON($bahanBaku);
        } catch (\Exception $e) {
            log_message('error', 'Error getting bahan baku: ' . $e->getMessage());
            return $this->response->setJSON([]);
        }
    }

    private function updateBahanBakuStock($bahanBakuId, $stockChange)
    {
        try {
            $bahanBaku = $this->bahanBakuModel->find($bahanBakuId);
            
            if ($bahanBaku) {
                $currentStock = $bahanBaku['stok'] ?? 0;
                $newStock = max(0, $currentStock + $stockChange);
                
                $this->bahanBakuModel->update($bahanBakuId, ['stok' => $newStock]);
                
                log_message('info', "Updated bahan_baku stock: ID={$bahanBakuId}, change={$stockChange}, new_stock={$newStock}");
            }
        } catch (\Exception $e) {
            log_message('error', "Failed to update bahan_baku stock: " . $e->getMessage());
        }
    }
}
