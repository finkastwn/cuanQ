<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianBahanModel extends Model
{
    protected $table            = 'pembelian_bahan';
    protected $primaryKey       = 'id';
    
    protected $allowedFields = [
        'tanggal_pembelian',
        'nama_pembelian',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
