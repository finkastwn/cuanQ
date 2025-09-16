<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToPesananTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pesanan', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pesanan_baru', 'dalam_proses', 'dikirim', 'selesai', 'dicairkan'],
                'default'    => 'pesanan_baru',
                'after'      => 'tanggal_pesanan',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pesanan', 'status');
    }
}