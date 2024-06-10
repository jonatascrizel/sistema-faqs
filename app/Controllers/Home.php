<?php

namespace App\Controllers;
use App\Models\FaqModel;
use App\Models\CategoriasModel;

class Home extends BaseController
{
    public function __construct(){
        $this->CategoriasModel = new CategoriasModel();
        $this->FaqModel = new FaqModel();
      }

    public function index()
    {
        $data['session'] = $this->session->get();
        $data['eventos'] = $this->FaqModel->selectCategorias();
        return view_site('site/faq',$data);
      }


}
