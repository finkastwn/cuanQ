<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePesananBahanUsageTable extends Migration
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
            'pesanan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'bahan_baku_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'pembelian_bahan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Which specific purchase batch this usage comes from',
            ],
            'quantity_used' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'How many pieces/units used from this batch',
            ],
            'hpp_per_unit' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'HPP per unit from the specific pembelian_bahan batch',
            ],
            'total_hpp' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'quantity_used * hpp_per_unit',
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
        $this->forge->addForeignKey('pesanan_id', 'pesanan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('bahan_baku_id', 'bahan_baku', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('pembelian_bahan_id', 'pembelian_bahan', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('pesanan_bahan_usage');
    }

    public function down()
    {
        $this->forge->dropTable('pesanan_bahan_usage');
    }
}
