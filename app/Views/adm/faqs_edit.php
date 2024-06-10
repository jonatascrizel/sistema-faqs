<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 vh-100">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">FAQs > Editar</h1>
  </div>

  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Informações</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab" aria-controls="profile" aria-selected="false">Manual do usuário</button>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

      <form id="formCadastro" method="post" action="<?= base_url('faq/update') ?>" class="mb-5 mt-3">
        <input type="hidden" name="id" id="id" value="<?= $txt->id ?>" />

        <div class="mb-3">
          <label for="nome" class="form-label">Categorias:</label>
            <?php
            //var_dump($faqEventos); die;
            $arr_fe = explode("|",$faqEventos[0]->faqs);
            foreach($eventos as $t) {
            ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="evento<?=$t->id?>" name="evento[]" value="<?=$t->id?>" <?php if(in_array($t->id,$arr_fe)) echo "checked"; ?> />
              <label class="form-check-label" for="evento<?=$t->id?>"><?=$t->nome?></label>
            </div>
            <?php } ?>
        </div>

        <div class="mb-3">
          <label for="pergunta" class="form-label">Pergunta:</label>
          <input type="text" class="form-control" id="pergunta" name="pergunta" value="<?= $txt->pergunta ?>" />
        </div>

        <div class="mb-3">
          <label for="resposta" class="form-label">Resposta:</label>
          <textarea class="form-control" id="resposta" name="resposta"><?= $txt->resposta ?></textarea>
        </div>

        <button type="button" onclick="submitForm('formCadastro')" class="btn btn-secondary">Salvar</button>

      </form>

    </div>
    <div class="tab-pane fade" id="manual" role="tabpanel" aria-labelledby="profile-tab">
      <?= $manual ?>
    </div>
  </div>

</main>
<script>
  window.onload = function() {
    ckeditor("resposta");
  }
</script>