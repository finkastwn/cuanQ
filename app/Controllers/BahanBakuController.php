<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BahanBakuModel;

class BahanBakuController extends BaseController
{
    public function __construct()
    {
        $this->bahanBakuModel = new BahanBakuModel();
    }

    public function index()
    {
        $data['bahan_baku'] = $this->bahanBakuModel
                        ->findAll();
        return view('bahan-baku/index', $data);
    }
}
