<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBahanBakuTable extends Migration
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
            'nama_bahan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'hpp' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'           => true,
            ],
            'stok' => [
               'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'           => true,
            ],
            'id_pemakaian_bahan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'id_pembelian_bahan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
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
        $this->forge->createTable('bahan_baku');
    }

    public function down()
    {
        $this->forge->dropTable('bahan_baku');
    }
}