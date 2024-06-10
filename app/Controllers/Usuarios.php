<?php

namespace App\Controllers;

//require('../vendor/autoload.php');

use App\Models\UserModel;
use App\Models\manualModel;

class usuarios extends BaseController
{

    public function __construct()
    {
        //parent::__construct();
        $this->UserModel = new UserModel();
        $this->manualModel = new manualModel();
        $this->manual = $this->manualModel->where('id', 1)->first()->manual;
    }


    public function index()
    {
        return redirect()->to(base_url('usuarios/listar'));
    }

    public function listar()
    {
        $data['users'] = $this->UserModel->orderBy('user_name', 'asc')->findAll();
        $data['manual'] = $this->manual;

        echo view_adm('adm/usuarios', $data);
    }

    public function add()
    {
        $data['manual'] = $this->manual;
        $data['menus'] = $this->UserModel->getMenus();

        echo view_adm('adm/usuariosAdd', $data);
    }

    public function store()
    {
        $data = [
            'user_name' => $this->request->getVar('user_name'),
            'user_email'  => $this->request->getVar('user_email'),
            'user_password'  => password_hash($this->request->getVar('user_password'), PASSWORD_DEFAULT),
            'ativo'  => $this->request->getVar('ativo'),
        ];
        $save = $this->UserModel->insert($data);

        $this->UserModel->salvaMenusUsuario($this->request->getVar('menus'), $save);

        return redirect()->to(base_url('usuarios'));
    }

    public function edit($id)
    {
        $data['users'] = $this->UserModel->where('id', $id)->first();
        $data['manual'] = $this->manual;
        $data['menus'] = $this->UserModel->getMenusUsuario($id);

        echo view_adm('adm/usuariosEdit', $data);
    }

    public function update()
    {
        $data = [
            'user_name' => $this->request->getVar('user_name'),
            'user_email'  => $this->request->getVar('user_email'),
            'ativo'  => $this->request->getVar('ativo'),
        ];
        //echo '<pre>'; var_dump($data); die;
        $save = $this->UserModel->update($this->request->getVar('id'), $data);

        $this->UserModel->salvaMenusUsuario($this->request->getVar('menus'), $this->request->getVar('id'));

        return redirect()->to(base_url('usuarios'));
    }

    public function delete($id)
    {
        $data['nice'] = $this->UserModel->where('id', $id)->delete();
        return redirect()->to(base_url('usuarios'));
    }

    public function aGeraMenu()
    {
        //$this->session->get('user_id')
        $menus = $this->UserModel->geraMenu($this->session->get('user_id'));
        foreach ($menus as $v) {
            $data[] = [
                'nome' => $v->nome,
                'icone' => $v->icone,
                'title' => $v->title,
                'controller' => base_url('/' . $v->controller),
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
