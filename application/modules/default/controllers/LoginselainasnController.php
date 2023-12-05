<?php
class LoginselainasnController extends Zend_Controller_Action {
    
    public function init()
    {
        // Inisialisasi controller
        $this->_helper->_acl->allow();
        $this->userService = new UserService();
    }

    public function indexAction()
    {
        // Tampilkan halaman login
    }

    public function loginAction()
    {
        $username = $this->_getParam('username');
        $password = $this->_getParam('password');
        
        $user = $this->userService->authenticate($username, $password);
        if ($user !== false) {

            $session = new Zend_Session_Namespace('loggedInUser');
            // $session->username = $username;
            $session->user = $user;
            // var_dump($session->user); die();
            $this->_redirect('/portalnonasn/index');
        } else {
            $session = new Zend_Session_Namespace('loginError');
            $session->message = 'Username atau Password Salah!';
            $this->_redirect('/');
        }
    }

    public function logoutAction()
    {
        Zend_Session::destroy(true);
        $this->_redirect('/');
    }
}
