<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanBakuModel extends Model
{
    protected $table            = 'bahan_baku';
    protected $primaryKey       = 'id';
    
    protected $allowedFields = [
        'nama_bahan',
        'hpp',
        'stok',
        'id_pemakaian_bahan',
        'id_pembelian_bahan',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
