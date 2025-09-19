<?php

namespace App\Libraries;

class StockService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getAvailableStockFIFO($bahanBakuId)
    {
        try {
            $query = $this->db->query("
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
                log_message('error', 'Query failed in StockService::getAvailableStockFIFO for bahan_baku_id: ' . $bahanBakuId);
                log_message('error', 'Database error: ' . $this->db->error()['message']);
                return [];
            }
            
            return $query->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Exception in StockService::getAvailableStockFIFO: ' . $e->getMessage());
            return [];
        }
    }

    public function allocateStockForPesanan($pesananId, $bahanBakuId, $quantityNeeded)
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
                    'pesanan_id' => (int) $pesananId,
                    'bahan_baku_id' => (int) $bahanBakuId,
                    'pembelian_bahan_id' => (int) $batch['pembelian_bahan_id'],
                    'quantity_used' => (int) $quantityFromThisBatch,
                    'hpp_per_unit' => (int) $batch['hpp_per_unit'],
                    'total_hpp' => (int) ($quantityFromThisBatch * $batch['hpp_per_unit'])
                ];
                
                $remainingQuantity -= $quantityFromThisBatch;
            }
        }

        if ($remainingQuantity > 0) {
            throw new \Exception("Stok tidak mencukupi. Dibutuhkan {$quantityNeeded}, tersedia: " . ($quantityNeeded - $remainingQuantity));
        }

        return $allocations;
    }

    public function allocateStockForManualUsage($bahanBakuId, $quantityNeeded, $purpose, $description = '', $usageDate = null)
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

    public function getTotalAvailableStock($bahanBakuId)
    {
        $availableBatches = $this->getAvailableStockFIFO($bahanBakuId);
        $totalStock = 0;

        foreach ($availableBatches as $batch) {
            $totalStock += $batch['remaining_stock'];
        }

        return $totalStock;
    }
}
