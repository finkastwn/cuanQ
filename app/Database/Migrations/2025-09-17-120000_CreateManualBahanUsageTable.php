<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateManualBahanUsageTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'bahan_baku_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'pembelian_bahan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'quantity_used' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'hpp_per_unit' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'total_hpp' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'purpose' => [
                'type' => 'ENUM',
                'constraint' => ['freebie', 'thank_you_card', 'other'],
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'usage_date' => [
                'type' => 'DATE',
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
        $this->forge->addKey('bahan_baku_id');
        $this->forge->addKey('pembelian_bahan_id');
        $this->forge->addKey('usage_date');
        $this->forge->addKey('purpose');

        $this->forge->createTable('manual_bahan_usage');
    }

    public function down()
    {
        $this->forge->dropTable('manual_bahan_usage');
    }
}
