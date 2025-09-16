<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table            = 'pesanan';
    protected $primaryKey       = 'id';
    
    protected $allowedFields = [
        'nama_pembeli',
        'source_penjualan',
        'tanggal_pesanan',
        'ada_biaya_potongan',
        'biaya_pemrosesan',
        'subtotal',
        'total_biaya_admin',
        'total_harga',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
