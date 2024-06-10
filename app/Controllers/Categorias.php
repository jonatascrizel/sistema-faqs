<?php

namespace App\Controllers;
use App\Models\manualModel;
use App\Models\CategoriasModel;


class Categorias extends BaseController
{
    public function __construct(){
      $this->CategoriasModel = new CategoriasModel();
      $this->manualModel = new manualModel();
      $this->manual = $this->manualModel->where('id', 23)->first()->manual;
    }

    public function listar()
    {
        $data['texto'] = $this->CategoriasModel->findAll();
        $data['manual'] = $this->manual;

        echo view_adm('adm/categorias', $data);
    }

    public function edit($id)
    {
      $data['txt'] = $this->CategoriasModel->where('id', $id)->first();
      $data['manual'] = $this->manual;

      //echo '<pre>'; var_dump($data); die;
      echo view_adm('adm/categorias_edit', $data);
    }
  
    public function update()
    {
      $data = [
        'nome' => $this->request->getVar('nome'),
      ];
      //echo '<pre>'; var_dump($data); die;
      $save = $this->CategoriasModel->update($this->request->getVar('id'), $data);
 
      return redirect()->to(base_url('categorias/listar'));
    }
  
    public function add() {
      $data['eventos'] = $this->CategoriasModel->orderBy('nome', 'asc')->findAll();
      $data['manual'] = $this->manual;

      echo view_adm('adm/categorias_add', $data);
    }

    public function store(){
      $data = [
        'nome' => $this->request->getVar('nome'),
      ];
      //echo '<pre>'; var_dump($data); die;
      $save = $this->CategoriasModel->insert($data);
  
      return redirect()->to(base_url('categorias/listar'));
    }

    public function delete($id)
    {
        $this->CategoriasModel->delete($id);
        return redirect()->to(base_url('categorias/listar'));
    }


}
