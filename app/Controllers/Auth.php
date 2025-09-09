<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        log_message('debug', '==== Auth: Showing login page ====');
        $session = session();
        if ($session->get('isLoggedIn')) {
            log_message('debug', 'User already logged in, redirecting to dashboard');
            return redirect()->to('/');
        }
        return view('auth/login');
    }

    public function doLogin()
    {
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        log_message('debug', '==== Auth: Processing login attempt ====');
        log_message('debug', "Login attempt for username: $username");

        $user = $this->userModel->getUserByUsername($username);

        if ($user) {
            log_message('debug', 'User found in database: ' . $user['username']);
            
            if (password_verify($password, $user['password'])) {
                $sessionData = [
                    'user_id'    => $user['id'],           
                    'username'   => $user['username'],
                    'name'       => $user['name'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);
                
                log_message('debug', 'Login successful. Session data set: ' . json_encode($sessionData));
                return redirect()->to('/');
            } else {
                log_message('debug', 'Password verification failed');
            }
        } else {
            log_message('debug', 'User not found in database');
        }

        log_message('debug', 'Login failed - redirecting back to login page');
        $session->setFlashdata('error', 'Invalid username or password.');
        return redirect()->to('/login');
    }

    public function logout()
    {
        log_message('debug', '==== Auth: Processing logout ====');
        log_message('debug', 'Current session data before logout: ' . json_encode(session()->get()));
        
        session()->destroy();
        log_message('debug', 'Session destroyed, redirecting to login page');
        
        return redirect()->to('/login');
    }
}
