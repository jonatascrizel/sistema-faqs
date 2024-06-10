
	<section id="topo" class="">
    <div class="container">
      <h1>Perguntas frequentes</h1>
    </div>
	</section>

  <section id="faq" class="py-4">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-4">
          <h3>Categorias</h3>
          <ul class="faqs-eventos mb-5">
            <?php
            foreach($eventos as $e) {
            ?>
              <li><a onclick="loadFaqs('<?=$e->id?>')"><?=$e->nome?><span class="float-end pe-1 badge  bg-light text-dark"><?=$e->contador?></span></a></li>
            <?php  
            }
            ?>
          </ul>
        </div>
        <div class="col-12 col-md-8 bg-light rounded p-3">
          <h3 class="text-dark">Perguntas e respostas</h3>
          <div class="accordion accordion-flush" id="perguntaserespostas">
            <strong>Clique na categoria ao lado</strong>
          </div>
        </div>

      </div>
    </div>
  </section>


