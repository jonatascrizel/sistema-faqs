<?php

namespace App\Controllers;

//require('../vendor/autoload.php');

use App\Models\configModel;
use App\Models\manualModel;

class configuracoes extends BaseController
{
  public $ConfigModel;

  public function __construct()
  {
    //parent::__construct();
    $this->ConfigModel = new configModel();
    $this->manualModel = new manualModel();
    $this->manual = $this->manualModel->where('id', 2)->first()->manual;
  }

  public function index()
  {
    return redirect()->to(base_url('configuracoes/listar'));
  }

  public function listar()
  {
    $data['cfgItens'] = $this->ConfigModel->orderBy('configName', 'asc')->findAll();
    $data['manual'] = $this->manual;

    echo view_adm('adm/configuracoes_lista', $data);
  }

  public function edit($id)
  {
    $data['cfgItem'] = $this->ConfigModel->where('id', $id)->first();
    $data['manual'] = $this->manual;

    echo view_adm('adm/configuracoes_edit', $data);
  }

  public function update()
  {
    $data = [
      'configValue' => $this->request->getVar('configValue'),
    ];
    //echo '<pre>'; var_dump($data); die;
    $save = $this->ConfigModel->update($this->request->getVar('id'), $data);

    return redirect()->to(base_url('configuracoes'));
  }
}
