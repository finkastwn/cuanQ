<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        log_message('debug', '==== AuthFilter: Starting authentication check ====');
        log_message('debug', 'Current URI: ' . current_url());
        log_message('debug', 'Session data: ' . json_encode($session->get()));
        
        if (!$session->get('isLoggedIn')) {
            log_message('debug', 'AuthFilter: User is not logged in, redirecting to login page');
            return redirect()->to('/login');
        } else {
            log_message('debug', 'AuthFilter: User is logged in as: ' . $session->get('username'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        log_message('debug', '==== AuthFilter: After filter executed ====');
    }
}
