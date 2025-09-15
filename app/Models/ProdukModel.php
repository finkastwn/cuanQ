<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id';
    
    protected $allowedFields = [
        'nama_produk',
        'harga_produk',
        'promo_type',
        'promo_active',
        'promo_start',
        'promo_end'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
