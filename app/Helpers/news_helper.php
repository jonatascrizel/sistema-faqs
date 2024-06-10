<?php

function sectionNews() {
  $html = '	<section id="news" class="bg-secondary py-4">
  <div class="container">
    <form id="cadastraNews" name="cadastraNews" method="post">
      <div class="row" id="newsReplace">
        <div class="col-md-5 col-12">
          <p>Entre para nossa lista e receba conteúdos exclusivos e com prioridade!</p>
        </div>
        <div class="col-md-6 col-12 pt-3 pt-md-0">
          <label for="inputEmail4" class="form-label d-none">Email</label>
          <input type="email" class="form-control email" id="emailNews" name="emailNews" placeholder="Seu melhor e-mail" required />
        </div>
        <div class="col-12 col-md-1 pt-3 pt-md-0"><button type="button" class="btn btn-lilas" id="cadastraNewsBtn">Enviar</button></div>
      </div>
    </form>
  </div>
</section>
';

return $html;
}

