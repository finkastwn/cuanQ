<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PesananModel;
use App\Models\PesananItemModel;
use App\Models\ProdukModel;

class PesananController extends BaseController
{
    protected $pesananModel;
    protected $pesananItemModel;
    protected $produkModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->pesananItemModel = new PesananItemModel();
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $data['pesanan'] = $this->pesananModel
                        ->orderBy('created_at', 'DESC')
                        ->findAll();
        $data['produk'] = $this->produkModel->findAll();
        return view('pesanan/index', $data);
    }
    
    public function detail($pesananId)
    {
        $data['pesanan'] = $this->pesananModel->find($pesananId);
        
        if (!$data['pesanan']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['items'] = $this->pesananItemModel
                              ->where('pesanan_id', $pesananId)
                              ->findAll();
        
        return view('pesanan/detail', $data);
    }

    public function store()
    {
        $namaPembeli = $this->request->getPost('nama_pembeli');
        $sourcePenjualan = $this->request->getPost('source_penjualan');
        $tanggalPesanan = $this->request->getPost('tanggal_pesanan');
        $adaBiayaPotongan = $this->request->getPost('ada_biaya_potongan') ? 1 : 0;
        $biayaPemrosesan = $this->request->getPost('biaya_pemrosesan');
        
        if (empty($namaPembeli)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Pembeli Wajib Diisi']);
        }
        
        if (empty($tanggalPesanan)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tanggal Pesanan Wajib Diisi']);
        }
        
        $produkIds = $this->request->getPost('produk_id');
        $jumlahProduk = $this->request->getPost('jumlah_produk');
        $biayaAdminPersen = $this->request->getPost('biaya_admin_persen');
        
        if (empty($produkIds)) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Minimal 1 produk harus diisi.']);
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $subtotal = 0;
            $totalBiayaAdmin = 0;
            $itemsToInsert = [];
            
            foreach ($produkIds as $key => $produkId) {
                $produk = $this->produkModel->find($produkId);
                if (!$produk) continue;
                
                $jumlah = $jumlahProduk[$key];
                $hargaProduk = $produk['harga_final'] ?? $produk['harga_produk'];
                $subtotalItem = $jumlah * $hargaProduk;
                
                $biayaAdminNominal = 0;
                if ($adaBiayaPotongan && isset($biayaAdminPersen[$key])) {
                    $adminPersen = floatval($biayaAdminPersen[$key]);
                    $biayaAdminNominal = ($subtotalItem * $adminPersen) / 100;
                    $totalBiayaAdmin += $biayaAdminNominal;
                }
                
                $subtotal += $subtotalItem;
                
                $itemsToInsert[] = [
                    'produk_id' => $produkId,
                    'nama_produk' => $produk['nama_produk'],
                    'jumlah_produk' => $jumlah,
                    'harga_produk' => $hargaProduk,
                    'biaya_admin_persen' => $adaBiayaPotongan ? ($biayaAdminPersen[$key] ?? 0) : 0,
                    'biaya_admin_nominal' => $biayaAdminNominal,
                    'subtotal_item' => $subtotalItem,
                ];
            }
            
            $totalHarga = $subtotal - $totalBiayaAdmin - ($biayaPemrosesan ?? 0);
            $totalHarga = max(0, $totalHarga);
            
            $dataPesanan = [
                'nama_pembeli' => $namaPembeli,
                'source_penjualan' => $sourcePenjualan,
                'tanggal_pesanan' => $tanggalPesanan,
                'ada_biaya_potongan' => $adaBiayaPotongan,
                'biaya_pemrosesan' => $biayaPemrosesan ?? 0,
                'subtotal' => $subtotal,
                'total_biaya_admin' => $totalBiayaAdmin,
                'total_harga' => $totalHarga
            ];

            $pesananId = $this->pesananModel->insert($dataPesanan);
            
            if ($pesananId) {
                foreach ($itemsToInsert as &$item) {
                    $item['pesanan_id'] = $pesananId;
                }
                
                $this->pesananItemModel->insertBatch($itemsToInsert);

                $db->transComplete();

                if ($db->transStatus() === false) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menyimpan Data.']);
                }

                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Pesanan Berhasil Disimpan!',
                    'data' => ['pesanan_id' => $pesananId]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menyimpan Pesanan.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function delete()
    {
        $pesananId = $this->request->getPost('id');
        
        if (empty($pesananId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Pesanan Wajib Diisi']);
        }
        
        $existingPesanan = $this->pesananModel->find($pesananId);
        if (!$existingPesanan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesanan Tidak Ditemukan']);
        }
        
        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $this->pesananItemModel->where('pesanan_id', $pesananId)->delete();
            $deleted = $this->pesananModel->delete($pesananId);
            
            $db->transComplete();
            
            if ($db->transStatus() === false || !$deleted) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menghapus Pesanan']);
            }
            
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Pesanan Berhasil Dihapus!'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
