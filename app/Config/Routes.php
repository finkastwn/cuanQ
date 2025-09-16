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
$routes->post('/bahan-baku/store', 'BahanBakuController::store', ['filter' => 'auth']);
$routes->post('/bahan-baku/update', 'BahanBakuController::update', ['filter' => 'auth']);
$routes->post('/bahan-baku/delete', 'BahanBakuController::delete', ['filter' => 'auth']);

$routes->get('/pembelian-bahan', 'PembelianBahanController::index', ['filter' => 'auth']);
$routes->get('/pembelian-bahan/detail/(:num)', 'PembelianBahanController::detail/$1', ['filter' => 'auth']);
$routes->post('/pembelian-bahan/store', 'PembelianBahanController::store', ['filter' => 'auth']);
$routes->post('/pembelian-bahan/update', 'PembelianBahanController::update', ['filter' => 'auth']);
$routes->post('/pembelian-bahan/delete', 'PembelianBahanController::delete', ['filter' => 'auth']);