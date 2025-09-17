<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout', ['filter' => 'auth']);

$routes->get('/produk', 'ProdukController::index', ['filter' => 'auth']);
$routes->get('/produk/create', 'ProdukController::create', ['filter' => 'auth']);
$routes->post('/produk/store', 'ProdukController::store_produk', ['filter' => 'auth']);
$routes->post('/produk/update', 'ProdukController::update_produk', ['filter' => 'auth']);
$routes->post('/produk/delete', 'ProdukController::delete_produk', ['filter' => 'auth']);
$routes->post('/produk/promo/store', 'ProdukController::store_promo', ['filter' => 'auth']);
$routes->get('/produk/promo/view/(:num)', 'ProdukController::view_promo/$1', ['filter' => 'auth']);
$routes->post('/produk/promo/delete', 'ProdukController::delete_promo', ['filter' => 'auth']);

$routes->get('/bahan-baku', 'BahanBakuController::index', ['filter' => 'auth']);
$routes->get('/bahan-baku/get-all', 'BahanBakuController::getAll', ['filter' => 'auth']);
$routes->post('/bahan-baku/store', 'BahanBakuController::store', ['filter' => 'auth']);
$routes->post('/bahan-baku/update', 'BahanBakuController::update', ['filter' => 'auth']);
$routes->post('/bahan-baku/delete', 'BahanBakuController::delete', ['filter' => 'auth']);

$routes->get('/pembelian-bahan', 'PembelianBahanController::index', ['filter' => 'auth']);
$routes->get('/pembelian-bahan/detail/(:num)', 'PembelianBahanController::detail/$1', ['filter' => 'auth']);
$routes->post('/pembelian-bahan/store', 'PembelianBahanController::store', ['filter' => 'auth']);
$routes->post('/pembelian-bahan/update', 'PembelianBahanController::update', ['filter' => 'auth']);
$routes->post('/pembelian-bahan/delete', 'PembelianBahanController::delete', ['filter' => 'auth']);

$routes->get('/pesanan', 'PesananController::index', ['filter' => 'auth']);
$routes->get('/pesanan/detail/(:num)', 'PesananController::detail/$1', ['filter' => 'auth']);
$routes->get('/pesanan/get-bahan-baku-usage/(:num)', 'PesananController::getBahanBakuUsage/$1', ['filter' => 'auth']);
$routes->get('/pesanan/get-available-stock/(:num)', 'PesananController::getAvailableStock/$1', ['filter' => 'auth']);
$routes->post('/pesanan/store', 'PesananController::store', ['filter' => 'auth']);
$routes->post('/pesanan/update', 'PesananController::update', ['filter' => 'auth']);
$routes->post('/pesanan/delete', 'PesananController::delete', ['filter' => 'auth']);
$routes->post('/pesanan/add-bahan-baku-usage', 'PesananController::addBahanBakuUsage', ['filter' => 'auth']);
$routes->delete('/pesanan/delete-bahan-baku-usage/(:num)', 'PesananController::deleteBahanBakuUsage/$1', ['filter' => 'auth']);
$routes->post('/pesanan/bulk-update-status', 'PesananController::bulkUpdateStatus', ['filter' => 'auth']);

$routes->get('/keuangan', 'KeuanganController::index', ['filter' => 'auth']);
$routes->post('/keuangan/store', 'KeuanganController::store', ['filter' => 'auth']);
$routes->post('/keuangan/update', 'KeuanganController::update', ['filter' => 'auth']);
$routes->post('/keuangan/delete', 'KeuanganController::delete', ['filter' => 'auth']);
$routes->post('/keuangan/update-pesanan-status', 'KeuanganController::updatePesananStatus', ['filter' => 'auth']);

$routes->get('/manual-bahan-usage', 'ManualBahanUsageController::index', ['filter' => 'auth']);
$routes->post('/manual-bahan-usage/store', 'ManualBahanUsageController::store', ['filter' => 'auth']);
$routes->post('/manual-bahan-usage/update', 'ManualBahanUsageController::update', ['filter' => 'auth']);
$routes->post('/manual-bahan-usage/delete', 'ManualBahanUsageController::delete', ['filter' => 'auth']);
$routes->get('/manual-bahan-usage/get-bahan-baku', 'ManualBahanUsageController::getAllBahanBaku', ['filter' => 'auth']);
$routes->get('/manual-bahan-usage/get-available-stock/(:num)', 'ManualBahanUsageController::getAvailableStock/$1', ['filter' => 'auth']);