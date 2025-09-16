<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSourceMoneyToPembelianBahanTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pembelian_bahan', [
            'source_money' => [
                'type'       => 'ENUM',
                'constraint' => ['duit_pribadi', 'bank_account', 'shopee_pocket'],
                'default'    => 'duit_pribadi',
                'after'      => 'nama_pembelian',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pembelian_bahan', 'source_money');
    }
}