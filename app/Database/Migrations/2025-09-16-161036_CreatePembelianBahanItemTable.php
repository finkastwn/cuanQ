<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembelianBahanItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pembelian_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'nama_item' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'jumlah_item' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'harga_item' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pembelian_id', 'pembelian_bahan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pembelian_bahan_items');
    }

    public function down()
    {
        $this->forge->dropTable('pembelian_bahan_items');
    }
}