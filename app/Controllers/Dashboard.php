<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        
        log_message('debug', 'Dashboard accessed. Session data: ' . json_encode($session->get()));
        
        if (!$session->get('isLoggedIn')) {
            log_message('debug', 'User not logged in, redirecting to login page');
            return redirect()->to('/login');
        }

        return view('dashboard');
    }
}
