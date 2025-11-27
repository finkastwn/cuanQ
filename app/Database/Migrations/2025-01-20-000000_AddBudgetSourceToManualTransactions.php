<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBudgetSourceToManualTransactions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('manual_transactions', [
            'budget_source' => [
                'type'       => 'ENUM',
                'constraint' => ['hpp_bahan', 'hpp_jasa', 'keuntungan', ''],
                'default'    => '',
                'null'       => false,
                'after'      => 'source_money',
                'comment'    => 'Which budget this transaction reduces (only for pengeluaran)'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('manual_transactions', 'budget_source');
    }
}

