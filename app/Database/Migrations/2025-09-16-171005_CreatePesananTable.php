<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePesananTable extends Migration
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
            'nama_pembeli' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'source_penjualan' => [
                'type'       => 'ENUM',
                'constraint' => ['shopee', 'tiktok', 'facebook', 'twitter', 'instagram', 'whatsapp', 'offline', 'other'],
                'default'    => 'other',
            ],
            'tanggal_pesanan' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'ada_biaya_potongan' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
            ],
            'biaya_pemrosesan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'subtotal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'total_biaya_admin' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'total_harga' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
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
        $this->forge->createTable('pesanan');
    }

    public function down()
    {
        $this->forge->dropTable('pesanan');
    }
}