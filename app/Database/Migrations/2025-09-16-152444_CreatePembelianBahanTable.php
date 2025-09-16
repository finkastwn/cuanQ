<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembelianBahanTable extends Migration
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
            'nama_pembelian' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tanggal_pembelian' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->createTable('pembelian_bahan');
    }

    public function down()
    {
        $this->forge->dropTable('pembelian_bahan');
    }
}
