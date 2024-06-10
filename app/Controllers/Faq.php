<?php

namespace App\Controllers;
use App\Models\FaqModel;
use App\Models\manualModel;
use App\Models\CategoriasModel;


class Faq extends BaseController
{
    public function __construct(){
      $this->FaqModel = new FaqModel();
      $this->CategoriasModel = new CategoriasModel();
      $this->manualModel = new manualModel();
      $this->manual = $this->manualModel->where('id', 24)->first()->manual;
    }

    public function listar()
    {
        $data['texto'] = $this->FaqModel->listaFaqsEventos();
        $data['manual'] = $this->manual;

        echo view_adm('adm/faqs', $data);
    }

    public function edit($id)
    {
      $data['txt'] = $this->FaqModel->where('id', $id)->first();
      $data['eventos'] = $this->CategoriasModel->orderBy('nome', 'asc')->findAll();
      $data['faqEventos'] = $this->FaqModel->selectFV($id);
      $data['manual'] = $this->manual;

      //echo '<pre>'; var_dump($data); die;
      echo view_adm('adm/faqs_edit', $data);
    }
  
    public function update()
    {
      $data = [
        'pergunta' => $this->request->getVar('pergunta'),
        'resposta' => $this->request->getVar('resposta'),
      ];
      //echo '<pre>'; var_dump($data); die;
      $save = $this->FaqModel->update($this->request->getVar('id'), $data);

      $this->FaqModel->salvaEventoXFaq($this->request->getVar('evento'), $this->request->getVar('id'));
  
      return redirect()->to(base_url('faq/listar'));
    }
  
    public function add() {
      $data['eventos'] = $this->CategoriasModel->orderBy('nome', 'asc')->findAll();
      $data['manual'] = $this->manual;

      echo view_adm('adm/faqs_add', $data);
    }

    public function store(){
      $data = [
        'pergunta' => $this->request->getVar('pergunta'),
        'resposta' => $this->request->getVar('resposta'),
      ];
      //echo '<pre>'; var_dump($data); die;
      $save = $this->FaqModel->insert($data);

      $this->FaqModel->salvaEventoXFaq($this->request->getVar('evento'), $save);
  
      return redirect()->to(base_url('faq/listar'));
    }

    public function delete($id)
    {
        $this->FaqModel->deletar($id);
        return redirect()->to(base_url('faq/listar'));
    }



    public function aLoadFaqs($id_evento = null){
      $data = $this->FaqModel->faqsEvento($id_evento);
      $html = '';

      foreach($data as $k => $v){
        $html .= '
        <div class="accordion-item">
        <h4 class="accordion-header" id="heading'.$v->id.'">
          <a class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapse'.$v->id.'" aria-expanded="true" aria-controls="collapse'.$v->id.'">
          '.$v->pergunta.' <!--<span class="badge ms-1">'.$v->categoria.'</span>-->
          </a>
        </h4>
        <div id="collapse'.$v->id.'" class="accordion-collapse collapse" aria-labelledby="heading'.$v->id.'" data-bs-parent="#perguntaserespostas">
          <div class="accordion-body ms-4">'.nl2br($v->resposta).'</div>
        </div>
      </div>';
      }

      return $html;
    }
}
