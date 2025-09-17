<?php

namespace App\Models;

use CodeIgniter\Model;

class ManualBahanUsageModel extends Model
{
    protected $table = 'manual_bahan_usage';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'bahan_baku_id',
        'pembelian_bahan_id',
        'quantity_used',
        'hpp_per_unit',
        'total_hpp',
        'purpose',
        'description',
        'usage_date'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'bahan_baku_id' => 'required|integer',
        'pembelian_bahan_id' => 'required|integer',
        'quantity_used' => 'required|integer|greater_than[0]',
        'hpp_per_unit' => 'required|numeric|greater_than_equal_to[0]',
        'total_hpp' => 'required|numeric|greater_than_equal_to[0]',
        'purpose' => 'required|in_list[freebie,thank_you_card,other]',
        'description' => 'permit_empty|string',
        'usage_date' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'bahan_baku_id' => [
            'required' => 'Bahan Baku ID harus diisi',
            'integer' => 'Bahan Baku ID harus berupa angka'
        ],
        'pembelian_bahan_id' => [
            'required' => 'Pembelian Bahan ID harus diisi',
            'integer' => 'Pembelian Bahan ID harus berupa angka'
        ],
        'quantity_used' => [
            'required' => 'Jumlah yang digunakan harus diisi',
            'integer' => 'Jumlah harus berupa angka',
            'greater_than' => 'Jumlah harus lebih dari 0'
        ],
        'hpp_per_unit' => [
            'required' => 'HPP per unit harus diisi',
            'numeric' => 'HPP harus berupa angka',
            'greater_than_equal_to' => 'HPP tidak boleh negatif'
        ],
        'total_hpp' => [
            'required' => 'Total HPP harus diisi',
            'numeric' => 'Total HPP harus berupa angka',
            'greater_than_equal_to' => 'Total HPP tidak boleh negatif'
        ],
        'purpose' => [
            'required' => 'Tujuan penggunaan harus diisi',
            'in_list' => 'Tujuan penggunaan tidak valid'
        ],
        'usage_date' => [
            'required' => 'Tanggal penggunaan harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert = ['calculateTotalHpp'];
    protected $beforeUpdate = ['calculateTotalHpp'];

    protected function calculateTotalHpp(array $data)
    {
        if (isset($data['data']['quantity_used']) && isset($data['data']['hpp_per_unit'])) {
            $data['data']['total_hpp'] = $data['data']['quantity_used'] * $data['data']['hpp_per_unit'];
        }
        return $data;
    }

    public function getManualUsageWithDetails()
    {
        return $this->select('
            manual_bahan_usage.*,
            bahan_baku.nama_bahan,
            pembelian_bahan.nama_pembelian,
            pembelian_bahan.tanggal_pembelian
        ')
        ->join('bahan_baku', 'bahan_baku.id = manual_bahan_usage.bahan_baku_id')
        ->join('pembelian_bahan', 'pembelian_bahan.id = manual_bahan_usage.pembelian_bahan_id')
        ->orderBy('manual_bahan_usage.usage_date', 'DESC')
        ->orderBy('manual_bahan_usage.created_at', 'DESC')
        ->findAll();
    }

    public function getTotalHppByPurpose($purpose = null)
    {
        $builder = $this->select('SUM(total_hpp) as total_hpp');
        
        if ($purpose) {
            $builder->where('purpose', $purpose);
        }
        
        $result = $builder->first();
        return $result['total_hpp'] ?? 0;
    }

    public function getAvailableStockFIFO($bahanBakuId)
    {
        $db = \Config\Database::connect();
        
        try {
            $query = $db->query("
                SELECT 
                    pbi.id as pembelian_bahan_item_id,
                    pbi.pembelian_id as pembelian_bahan_id,
                    pbi.bahan_baku_id,
                    (pbi.jumlah_item * pbi.isi_per_unit) as total_purchased,
                    pbi.harga_per_unit as hpp_per_unit,
                    pb.tanggal_pembelian,
                    pb.nama_pembelian,
                    COALESCE(SUM(pbu.quantity_used), 0) as total_used_pesanan,
                    COALESCE(SUM(mbu.quantity_used), 0) as total_used_manual,
                    ((pbi.jumlah_item * pbi.isi_per_unit) - COALESCE(SUM(pbu.quantity_used), 0) - COALESCE(SUM(mbu.quantity_used), 0)) as remaining_stock
                FROM pembelian_bahan_items pbi
                JOIN pembelian_bahan pb ON pb.id = pbi.pembelian_id
                LEFT JOIN pesanan_bahan_usage pbu ON pbu.pembelian_bahan_id = pbi.pembelian_id 
                    AND pbu.bahan_baku_id = pbi.bahan_baku_id
                LEFT JOIN manual_bahan_usage mbu ON mbu.pembelian_bahan_id = pbi.pembelian_id 
                    AND mbu.bahan_baku_id = pbi.bahan_baku_id
                WHERE pbi.bahan_baku_id = ?
                GROUP BY pbi.id, pbi.pembelian_id, pbi.bahan_baku_id, pbi.jumlah_item, pbi.isi_per_unit, pbi.harga_per_unit, 
                         pb.tanggal_pembelian, pb.nama_pembelian
                HAVING remaining_stock > 0
                ORDER BY pb.tanggal_pembelian ASC, pbi.id ASC
            ", [$bahanBakuId]);
            
            if ($query === false) {
                log_message('error', 'Query failed in getAvailableStockFIFO for bahan_baku_id: ' . $bahanBakuId);
                log_message('error', 'Database error: ' . $db->error()['message']);
                return [];
            }
            
            return $query->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Exception in getAvailableStockFIFO: ' . $e->getMessage());
            return [];
        }
    }

    public function allocateStockFIFO($bahanBakuId, $quantityNeeded, $purpose, $description = '', $usageDate = null)
    {
        $availableBatches = $this->getAvailableStockFIFO($bahanBakuId);
        $allocations = [];
        $remainingQuantity = $quantityNeeded;

        if (empty($availableBatches)) {
            throw new \Exception("Tidak ada stok tersedia untuk bahan baku ini");
        }

        foreach ($availableBatches as $batch) {
            if ($remainingQuantity <= 0) break;

            $quantityFromThisBatch = min($remainingQuantity, $batch['remaining_stock']);
            
            if ($quantityFromThisBatch > 0) {
                $allocations[] = [
                    'bahan_baku_id' => (int) $bahanBakuId,
                    'pembelian_bahan_id' => (int) $batch['pembelian_bahan_id'],
                    'quantity_used' => (int) $quantityFromThisBatch,
                    'hpp_per_unit' => (float) $batch['hpp_per_unit'],
                    'total_hpp' => (float) ($quantityFromThisBatch * $batch['hpp_per_unit']),
                    'purpose' => $purpose,
                    'description' => $description,
                    'usage_date' => $usageDate ?: date('Y-m-d')
                ];
                
                $remainingQuantity -= $quantityFromThisBatch;
            }
        }

        if ($remainingQuantity > 0) {
            throw new \Exception("Stok tidak mencukupi. Dibutuhkan {$quantityNeeded}, tersedia: " . ($quantityNeeded - $remainingQuantity));
        }

        return $allocations;
    }

    public function deleteByBahanBaku($bahanBakuId)
    {
        return $this->where('bahan_baku_id', $bahanBakuId)->delete();
    }
}
