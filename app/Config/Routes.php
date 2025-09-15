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
$routes->post('/produk/promo/store', 'ProdukController::store_promo', ['filter' => 'auth']);
$routes->get('/produk/promo/view/(:num)', 'ProdukController::view_promo/$1', ['filter' => 'auth']);
$routes->post('/produk/promo/delete', 'ProdukController::delete_promo', ['filter' => 'auth']);