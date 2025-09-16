<?php

namespace App\Models;

use CodeIgniter\Model;

class FinancialSummaryModel extends Model
{
    protected $table            = 'financial_summary';
    protected $primaryKey       = 'id';
    
    protected $allowedFields = [
        'utang_total',
        'bank_account_balance',
        'shopee_pocket_balance',
        'last_updated',
    ];

    protected $useTimestamps = false;
}
