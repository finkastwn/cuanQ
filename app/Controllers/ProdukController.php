<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProdukModel;

class ProdukController extends BaseController
{
    protected $incomeCategoryModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $data['produk'] = $this->produkModel
                        ->findAll();
        return view('produk/index', $data);
    }
}
