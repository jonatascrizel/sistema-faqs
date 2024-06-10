<?php

function sectionInscricao($evento) {
  $html = '';
  
  if($evento->inscricao_start && $evento->inscricao_start <= date('Y-m-d') && $evento->inscricao_end >= date('Y-m-d')){
  
  $html .= '<div class="inscrevase">
    <h2 class="display-5">Garanta sua vaga AGORA!</h2>
    <p><strong>Vagas limitadas!</strong> Garantimos as vagas para os que primeiro se inscreverem.</p>
    <a class="btn btn-lilas" href="'.base_url('inscritos/inscrever/'.$evento->id).'">Quero me inscrever</a>
  </div>';
  
  }

return $html;
}

