<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PembelianBahanModel;

class PembelianBahanController extends BaseController
{
    protected $pembelianBahanModel;

    public function __construct()
    {
        $this->pembelianBahanModel = new PembelianBahanModel();
    }

    public function index()
    {
        $data['pembelianBahan'] = $this->pembelianBahanModel
                        ->findAll();
        return view('pembelian-bahan/index', $data);
    }
}