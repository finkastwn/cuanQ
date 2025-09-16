<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PembelianBahanModel;
use App\Models\PembelianBahanItemModel;
use App\Models\BahanBakuModel;

class PembelianBahanController extends BaseController
{
    protected $pembelianBahanModel;
    protected $pembelianBahanItemModel;
    protected $bahanBakuModel;

    public function __construct()
    {
        $this->pembelianBahanModel = new PembelianBahanModel();
        $this->pembelianBahanItemModel = new PembelianBahanItemModel();
        $this->bahanBakuModel = new BahanBakuModel();
    }

    public function index()
    {
        $data['pembelianBahan'] = $this->pembelianBahanModel
                        ->orderBy('tanggal_pembelian', 'DESC')
                        ->findAll();
        $data['bahanBaku'] = $this->bahanBakuModel->findAll();
        return view('pembelian-bahan/index', $data);
    }
    
    public function detail($pembelianId)
    {
        $data['pembelian'] = $this->pembelianBahanModel->find($pembelianId);
        
        if (!$data['pembelian']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['items'] = $this->pembelianBahanItemModel
                              ->where('pembelian_id', $pembelianId)
                              ->findAll();
        
        return view('pembelian-bahan/detail', $data);
    }

    public function store()
    {
        $namaPembelian = $this->request->getPost('nama_pembelian');
        $tanggalPembelian = $this->request->getPost('tanggal_pembelian');
        $adminFee = $this->request->getPost('admin_fee');
        $discount = $this->request->getPost('discount');
        $hargaTotal = $this->request->getPost('harga_total');
        
        if (empty($namaPembelian) || empty($tanggalPembelian)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama dan Tanggal Pembelian Wajib Diisi']);
        }
        
        $bahanBakuIds = $this->request->getPost('bahan_baku_id');
        $itemQuantities = $this->request->getPost('jumlah_item');
        $isiPerUnit = $this->request->getPost('isi_per_unit');
        $itemPrices = $this->request->getPost('harga_item');
        $hargaPerUnit = $this->request->getPost('harga_per_unit');
        
        if (empty($bahanBakuIds)) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Minimal 1 item harus diisi.']);
        }
        
        $dataPembelian = [
            'nama_pembelian' => $namaPembelian,
            'tanggal_pembelian' => $tanggalPembelian,
            'admin_fee' => $adminFee,
            'discount' => $discount,
            'harga_total' => $hargaTotal
        ];

        try {
            // Start transaction
            $db = \Config\Database::connect();
            $db->transStart();
            
            $pembelianId = $this->pembelianBahanModel->insert($dataPembelian);
            
            if ($pembelianId) {
                $itemsToInsert = [];
                
                foreach ($bahanBakuIds as $key => $bahanBakuId) {
                    // Get nama_bahan from bahan_baku table
                    $bahanBaku = $this->bahanBakuModel->find($bahanBakuId);
                    $namaItem = $bahanBaku ? $bahanBaku['nama_bahan'] : 'Unknown';
                    
                    $quantity = $itemQuantities[$key];
                    $isi = $isiPerUnit[$key];
                    $totalStok = $quantity * $isi; // Total pieces to add to stock
                    
                    $itemsToInsert[] = [
                        'pembelian_id' => $pembelianId,
                        'bahan_baku_id' => $bahanBakuId,
                        'nama_item' => $namaItem,
                        'jumlah_item' => $quantity,
                        'isi_per_unit' => $isi,
                        'harga_item' => $itemPrices[$key],
                        'harga_per_unit' => $hargaPerUnit[$key],
                    ];
                    
                    // Update stock and hpp in bahan_baku table
                    $currentBahanBaku = $this->bahanBakuModel->find($bahanBakuId);
                    if ($currentBahanBaku) {
                        $currentStok = $currentBahanBaku['stok'] ?? 0;
                        $newStok = $currentStok + $totalStok;
                        $newHpp = $hargaPerUnit[$key]; // HPP is price per piece
                        
                        $this->bahanBakuModel->update($bahanBakuId, [
                            'stok' => $newStok,
                            'hpp' => $newHpp
                        ]);
                    }
                }
                
                $this->pembelianBahanItemModel->insertBatch($itemsToInsert);
                
                $db->transComplete();

                if ($db->transStatus() === false) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menyimpan Data.']);
                }

                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Pembelian Bahan Berhasil Disimpan!',
                    'data' => ['pembelian_id' => $pembelianId]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menyimpan Pembelian Bahan.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}