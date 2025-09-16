<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminFeeAndDiscountToPembelianBahanTable extends Migration
{
    public function up()
    {
        $fields = [
            'admin_fee' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'nama_pembelian',
            ],
            'discount' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'admin_fee',
            ],
            'harga_total' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'discount',
            ],
        ];

        $this->forge->addColumn('pembelian_bahan', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('pembelian_bahan', ['admin_fee', 'discount', 'harga_total']);
    }
}