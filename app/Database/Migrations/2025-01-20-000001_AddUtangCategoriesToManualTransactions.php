<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUtangCategoriesToManualTransactions extends Migration
{
    public function up()
    {
        $this->db->query("
            ALTER TABLE manual_transactions 
            MODIFY COLUMN kategori ENUM('manual', 'pesanan', 'pembelian_bahan', 'manual_utang', 'pembayaran_utang') 
            DEFAULT 'manual'
        ");
    }

    public function down()
    {
        $this->db->query("
            ALTER TABLE manual_transactions 
            MODIFY COLUMN kategori ENUM('manual', 'pesanan', 'pembelian_bahan') 
            DEFAULT 'manual'
        ");
    }
}

