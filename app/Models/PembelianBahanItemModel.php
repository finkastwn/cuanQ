<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianBahanItemModel extends Model
{
    protected $table            = 'pembelian_bahan_items';
    protected $primaryKey       = 'id';
    
    protected $allowedFields = [
        'pembelian_id',
        'bahan_baku_id',
        'nama_item',
        'jumlah_item',
        'isi_per_unit',
        'harga_item',
        'harga_per_unit',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}