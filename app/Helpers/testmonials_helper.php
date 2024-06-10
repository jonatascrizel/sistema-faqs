<?php

function sectionTestmonials($testmonials) {
  $html = '';

  $html = '	<section id="depoiments">
  <div class="container pt-3">
    <div class="row text-center mb-5 px-5">
      <h1>O que nossos viajantes dizem</h1>
      <p>Levamos muitos jovens e voluntários, irmãos de ideal escoteiro, em diversas aventuras. Leia a opinião deles.</p>
    </div>
    <div class="row">
      <div class="col-12" id="carrousel_depoimentos">
      ';

  foreach($testmonials as $k => $v){
    $html .= '
              <div>
                <span>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </span>
                <p>'.$v['testmonial'].'</p>
                <img src="'.base_url('public/uploads/'.$v['foto']).'" />
                <h5 class="mb-0">'.$v['nome'].'</h5>
                <small>'.$v['uel'].'</small>
              </div>
            ';
  }

  $html .= '        
      </div>
    </div>
  </div>
</section>
';

return $html;
}

