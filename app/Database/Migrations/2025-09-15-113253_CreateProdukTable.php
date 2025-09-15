<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProdukTable extends Migration
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
            'nama_produk' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'harga_produk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'promo_type' => [
                'type'       => 'ENUM',
                'constraint' => ['none', 'percent', 'fixed'],
                'default'    => 'none',
            ],
            'promo_value' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'promo_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'promo_start' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'promo_end' => [
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
        $this->forge->createTable('produk');
    }

    public function down()
    {
        $this->forge->dropTable('produk');
    }
}