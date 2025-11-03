<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrintFieldsToPesanan extends Migration
{
    public function up()
    {
        $fields = [
            'print_pages' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
                'after' => 'total_harga',
            ],
            'print_cost' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
                'after' => 'print_pages',
            ],
        ];

        $this->forge->addColumn('pesanan', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('pesanan', 'print_cost');
        $this->forge->dropColumn('pesanan', 'print_pages');
    }
}


