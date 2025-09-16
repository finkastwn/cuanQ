<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsiAndBahanBakuIdToPembelianBahanItemsTable extends Migration
{
    public function up()
    {
        $fields = [
            'bahan_baku_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
                'after'          => 'pembelian_id',
            ],
            'isi_per_unit' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 1,
                'after'      => 'jumlah_item',
            ],
            'harga_per_unit' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'after'      => 'harga_item',
            ],
        ];

        $this->forge->addColumn('pembelian_bahan_items', $fields);
        
        // Add foreign key constraint for bahan_baku_id
        $this->forge->addForeignKey('bahan_baku_id', 'bahan_baku', 'id', 'SET NULL', 'CASCADE', 'pembelian_bahan_items');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pembelian_bahan_items', 'pembelian_bahan_items_bahan_baku_id_foreign');
        $this->forge->dropColumn('pembelian_bahan_items', ['bahan_baku_id', 'isi_per_unit', 'harga_per_unit']);
    }
}