<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananItemModel extends Model
{
    protected $table            = 'pesanan_items';
    protected $primaryKey       = 'id';
    
    protected $allowedFields = [
        'pesanan_id',
        'produk_id',
        'nama_produk',
        'jumlah_produk',
        'harga_produk',
        'biaya_admin_persen',
        'biaya_admin_nominal',
        'subtotal_item',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
