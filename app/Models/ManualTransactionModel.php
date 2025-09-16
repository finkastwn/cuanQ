<?php

namespace App\Models;

use CodeIgniter\Model;

class ManualTransactionModel extends Model
{
    protected $table            = 'manual_transactions';
    protected $primaryKey       = 'id';
    
    protected $allowedFields = [
        'tanggal',
        'keterangan',
        'type',
        'source_money',
        'jumlah',
        'kategori',
        'reference_id',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
