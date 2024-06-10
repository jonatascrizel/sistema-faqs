<?php

namespace App\Controllers;

//require('../vendor/autoload.php');

use App\Models\textosModel;
use App\Models\manualModel;

class manuais extends BaseController
{
  public function __construct()
  {
    //parent::__construct();
    $this->manualModel = new manualModel();
    $this->manual = $this->manualModel->where('id', 7)->first()->manual;
  }

  public function index()
  {
    return redirect()->to(base_url('manuais/listar'));
  }

  public function listar()
  {
    $data['textos'] = $this->manualModel->orderBy('nome', 'asc')->findAll();
    $data['manual'] = $this->manual;

    echo view_adm('adm/manuais_lista', $data);
  }

  public function ver($id)
  {
    $txt = $this->manualModel->where('id', $id)->first();

    $html = '
      <div class="mb3">' . $txt->manual . '</div>
    ';
    return $html;
  }

  public function edit($id)
  {
    $data['txt'] = $this->manualModel->where('id', $id)->first();
    $data['manual'] = $this->manual;

    echo view_adm('adm/manuais_edit', $data);
  }

  public function update()
  {
    $data = [
      'manual' => $this->request->getVar('txtmanual'),
    ];
    //echo '<pre>'; var_dump($data); die;
    $save = $this->manualModel->update($this->request->getVar('id'), $data);

    return redirect()->to(base_url('manuais'));
  }
}
