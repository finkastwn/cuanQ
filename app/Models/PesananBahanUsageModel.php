<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananBahanUsageModel extends Model
{
    protected $table = 'pesanan_bahan_usage';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'pesanan_id',
        'bahan_baku_id', 
        'pembelian_bahan_id',
        'quantity_used',
        'hpp_per_unit',
        'total_hpp'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'pesanan_id' => 'required|integer',
        'bahan_baku_id' => 'required|integer',
        'pembelian_bahan_id' => 'required|integer',
        'quantity_used' => 'required|integer|greater_than[0]',
        'hpp_per_unit' => 'required|numeric|greater_than[0]',
        'total_hpp' => 'required|numeric|greater_than[0]'
    ];

    protected $validationMessages = [
        'pesanan_id' => [
            'required' => 'Pesanan ID harus diisi',
            'integer' => 'Pesanan ID harus berupa angka'
        ],
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
            'greater_than' => 'HPP harus lebih dari 0'
        ],
        'total_hpp' => [
            'required' => 'Total HPP harus diisi',
            'numeric' => 'Total HPP harus berupa angka',
            'greater_than' => 'Total HPP harus lebih dari 0'
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

    public function getBahanBakuUsageByPesanan($pesananId)
    {
        return $this->select('
            pesanan_bahan_usage.*,
            bahan_baku.nama_bahan,
            pembelian_bahan.nama_pembelian,
            pembelian_bahan.tanggal_pembelian
        ')
        ->join('bahan_baku', 'bahan_baku.id = pesanan_bahan_usage.bahan_baku_id')
        ->join('pembelian_bahan', 'pembelian_bahan.id = pesanan_bahan_usage.pembelian_bahan_id')
        ->where('pesanan_id', $pesananId)
        ->orderBy('bahan_baku.nama_bahan', 'ASC')
        ->findAll();
    }

    public function getTotalHppByPesanan($pesananId)
    {
        $result = $this->select('SUM(total_hpp) as total_hpp')
            ->where('pesanan_id', $pesananId)
            ->first();
        
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
                    pbi.isi_per_unit as total_purchased,
                    pbi.harga_per_unit as hpp_per_unit,
                    pb.tanggal_pembelian,
                    pb.nama_pembelian,
                    COALESCE(SUM(pbu.quantity_used), 0) as total_used,
                    (pbi.isi_per_unit - COALESCE(SUM(pbu.quantity_used), 0)) as remaining_stock
                FROM pembelian_bahan_items pbi
                JOIN pembelian_bahan pb ON pb.id = pbi.pembelian_id
                LEFT JOIN pesanan_bahan_usage pbu ON pbu.pembelian_bahan_id = pbi.pembelian_id 
                    AND pbu.bahan_baku_id = pbi.bahan_baku_id
                WHERE pbi.bahan_baku_id = ?
                GROUP BY pbi.id, pbi.pembelian_id, pbi.bahan_baku_id, pbi.isi_per_unit, pbi.harga_per_unit, 
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

    public function allocateStockFIFO($pesananId, $bahanBakuId, $quantityNeeded)
    {
        $availableBatches = $this->getAvailableStockFIFO($bahanBakuId);
        $allocations = [];
        $remainingQuantity = $quantityNeeded;

        foreach ($availableBatches as $batch) {
            if ($remainingQuantity <= 0) break;

            $quantityFromThisBatch = min($remainingQuantity, $batch['remaining_stock']);
            
            if ($quantityFromThisBatch > 0) {
                $allocations[] = [
                    'pesanan_id' => (int) $pesananId,
                    'bahan_baku_id' => (int) $bahanBakuId,
                    'pembelian_bahan_id' => (int) $batch['pembelian_bahan_id'],
                    'quantity_used' => (int) $quantityFromThisBatch,
                    'hpp_per_unit' => (float) $batch['hpp_per_unit'],
                    'total_hpp' => (float) ($quantityFromThisBatch * $batch['hpp_per_unit'])
                ];
                
                $remainingQuantity -= $quantityFromThisBatch;
            }
        }

        if ($remainingQuantity > 0) {
            throw new \Exception("Insufficient stock for {$bahanBakuId}. Need {$quantityNeeded}, available: " . ($quantityNeeded - $remainingQuantity));
        }

        return $allocations;
    }

    public function deleteByPesanan($pesananId)
    {
        return $this->where('pesanan_id', $pesananId)->delete();
    }
}
