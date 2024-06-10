<?php

namespace App\Controllers;

//require('../vendor/autoload.php');

use App\Models\UserModel;

class dashboard extends BaseController
{
    public function __construct()
    {
        //parent::__construct();
        $this->UserModel = new UserModel();
    }

    public function index()
    {
        $data = [];

        echo view_adm('adm/dashboard', $data);
    }

    public function trocaSenha()
    {
        $data = [];
        echo view_adm('adm/troca_senha', $data);
    }

    public function trocaSenhaDo()
    {
        if (!is_numeric($this->session->get('user_id'))) {
            return redirect()->to(base_url('/logout'));
        }

        $data = [
            'user_password' => password_hash($this->request->getVar('pws_nova'), PASSWORD_DEFAULT),
        ];
        $save = $this->UserModel->update($this->session->get('user_id'), $data);

        return redirect()->to(base_url('/dashboard'));
    }
}
