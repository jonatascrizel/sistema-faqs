$(window).on('load', function () {

  captchas = [];
  carregaForms();
  $('[title]').tooltip();

  $(".nav-link").on("click", function(){
    $('.navbar-collapse').collapse('hide');
 });

  $('#cadastraNewsBtn').on('click', function(){
    /*
    */
    if ($("#cadastraNews").valid()) {
      waitingDialog.show('Aguarde, processando', {
        dialogSize: 'lg',
        progressType: 'info'
      });

      $.ajax({
        type: 'post',
        url: '../../../Contato/aCadastraNews/' + $('#emailNews').val(),
        success: function (r) {
          waitingDialog.hide();
          $('#newsReplace').html('<div class="alert alert-success alert-dismissible d-flex align-items-center fade show" role="alert">'
                                  +'<i class="fas fa-check-circle fs-5 me-2"></i>'
                                  +'<div>Incluído com sucesso!</div>'
                                  +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'
                                +'</div>');
        },
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Verifique os campos em vermelho.',
        text: 'Preencha todos os dados corretamente',
        confirmButtonColor: '#d33'
      });
    }
  });

  $("#dd_acompanhante").hide();

  // rolagem suave
  $("a.roll").on('click', function (e) {
    var poss = $(this).attr('href').search("#");
    var id = $(this).attr('href').slice(poss);
    if($(id).length){
      e.preventDefault();
      targetOffset = $(id).offset().top;

      $('html, body').animate({
        scrollTop: targetOffset - 100
      }, 500);
    }
  });

  $(window).scroll(function () {
    var windowTop = $(window).scrollTop();
    var windowHeight = $(window).innerHeight();

    if (windowTop > 50) {
      $("#menu").addClass('bg-menu');
      $("#menu nav").removeClass('navbar-dark').addClass('navbar-light');
    } else {
      $("#menu").removeClass('bg-menu');
      $("#menu nav").removeClass('navbar-light').addClass('navbar-dark');
    }

    if (windowTop > (windowHeight / 2)) {
      $("#subir").show();
    } else {
      $("#subir").hide();
    }
  });

  /*
  $('.carrossel-topo').slick({
    dots: false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    adaptiveHeight: true
  });
  */

  $('#carroussel_eventos').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    centerMode: true,
    autoplay: true,
    autoplaySpeed: 2000,
    prevArrow: false,
    nextArrow: false,
    dots: false,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
        }
      }
    ]
  });

  $('#carrousel_depoimentos').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    centerMode: true,
    autoplay: true,
    autoplaySpeed: 2000,
    prevArrow: false,
    nextArrow: false,
    dots: true,
    responsive: [{
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }],
  });

}); //fim onload

function onloadCallback() {
  $('form button[type="button"]').removeAttr("disabled");
}
function recaptchaExpiredCallback() {
  grecaptcha.reset();
};

function submitForm(idForm) {
  if ($("#" + idForm).valid()) {
    //pede pro usuario aguardar
    waitingDialog.show('Aguarde, processando', {
      dialogSize: 'lg',
      progressType: 'info'
    });

    //verifica se foi renderizado
    if (typeof captchas["grec_" + idForm] == 'undefined') {
      captchas["grec_" + idForm] = grecaptcha.render("grec_" + idForm, {
        'sitekey': $("#grec_" + idForm).data('sitekey'),
        'size': "invisible",
        'expired-callback': "recaptchaExpired",
        'callback': function (success) {
          console.log('OK: ' + success);
          //submit
          $('#' + idForm).submit();
        }
      });
    }

    //garante que não tem poluição no reCaptcha
    grecaptcha.reset(captchas["grec_" + idForm]);
    //executa o reCaptcha
    grecaptcha.execute(captchas["grec_" + idForm]);

  } else {
    Swal.fire({
      icon: 'error',
      title: 'Verifique os campos em vermelho.',
      text: 'Preencha todos os dados corretamente',
      confirmButtonColor: '#d33'
    });
  }
}

function maskAll() {
  $('.date').mask('00/00/0000');
  $('.phone_with_ddd').mask('(00) #0000-0000');
  $('.whatsapp').mask('(00) 0 0000-0000');
  $('.cep').mask('00000-000');
  $('.money2').mask("#.###.##0,00", { reverse: true });
  $('.peso').mask("#0.000", { reverse: true });
  $('.taxa').mask("0.0000", { reverse: true });
  $('.taxa_comma').mask("0,0000", { reverse: true });
  $('.percentual').mask("000,00", {
    reverse: true,
    onKeyPress: function (val, e, field, options) {
      if (val.replace(',', '.') > 100.0) {
        console.log(field.prop('name') + ' excedeu o valor máximo de 100,00!');
        field.val('100,00');
      }
    }
  });
  $('.docCNPJ').mask('00.000.000/0000-00', { reverse: true });
  $('.cpf').mask('000.000.000-00', { reverse: true });
  $('.numeros').mask('#############0');
  $('.hora').mask("00:00", { reverse: true });
  $('.cpfcnpj').focusout(function () {
    var aux, element;
    element = $(this);
    element.unmask();
    aux = element.val().replace(/\D/g, '');

    if (aux.length > 11)
      element.mask("99.999.999/9999-99");
    else
      element.mask("999.999.999-99?999");
  }).focus(function () {
    var element;
    element = $(this);
    element.unmask();
  });
}

function resetForm(idElemento) {
  Swal.fire({
    icon: 'info',
    title: 'Deseja mesmo limpar todos dados do formulário?',
    text: '',
    showCancelButton: true,
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Apagar tudo',
    confirmButtonColor: '#d33',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      $("#" + idElemento).trigger('reset');
    }
  })
}

function carregaForms() {
  if($('form').length){
    $.getScript('https://www.google.com/recaptcha/api.js?hl=pt-BR&onload=onloadCallback&render=explicit');
    $.getScript(caminho + '/js/jquery.mask.js', function () {
      maskAll();
    });
    console.log('carregado');
  } else {
    console.log('sem forms');
  }
}

function loadFaqs(id_evento){
  waitingDialog.show('Aguarde, processando', {
    dialogSize: 'lg',
    progressType: 'info'
  });

  $.ajax({
    type: 'post',
    url: '../../../Faq/aLoadFaqs/' + id_evento,
    success: function (r) {
      $('#perguntaserespostas').html(r);
      waitingDialog.hide();
    },
  });
}


function buscaCep(codigo_input) {
  if ($('#' + codigo_input + '_cep').val() == '') {
      swal("Erro!", "Informe um CEP!", "error");
      return false;
  } else {
      waitingDialog.show('Processando. Por gentileza, aguarde.', { progressType: 'danger' });
      $.getJSON("https://viacep.com.br/ws/" + $('#' + codigo_input + '_cep').val() + "/json/?callback=?", function (dados) {
          waitingDialog.hide();
          if (!("erro" in dados)) {
              $("#" + codigo_input + "_endereco").val(dados.logradouro);
              //$("#cad_cidade").val(dados.localidade);
              //$("#cad_uf").val(dados.uf);
              $("#" + codigo_input + "_uf option[data-uf=" + dados.uf + "]").prop('selected', true);
              carrega_cidades(codigo_input + '_cidade', $("#" + codigo_input + "_uf option[data-uf=" + dados.uf + "]").val(), '', dados.localidade);
              $("#" + codigo_input + "_numero").focus();
          } else {
              swal("Esse CEP não foi encontrado.", '', "error");
          }
      });
  }
}

function carrega_cidades(destino, valor, selecionar, selecionarText) {
  if (valor == '') {
      swal("Erro!", "Selecione um estado antes!", "error");
      return false;
  } else {
      $.ajax({
          method: "GET",
          url: "../../../../Home/aMontaComboCidades/" + valor,
      })
          .done(function (msg) {
              $('#' + destino).html(msg);

              if (selecionar)
                  $('#' + destino).val(selecionar);

              if (selecionarText)
                  $("#" + destino + " option:contains('" + selecionarText + "')").prop('selected', true);
          });
  }
}

function checkAcompanhante() {
  if ($("#cd_acompanhado").is(':checked')) {  // checked
      $("#dd_acompanhante").show();
      $("#cd_ac_nome").attr("required", "true");
      $("#cd_ac_nascimento").attr("required", "true");
      $("#cd_ac_genero1").attr("required", "true");
      $("#cd_ac_cpf").attr("required", "true");
      $("#cd_ac_telefone").attr("required", "true");
      $("#cd_ac_email").attr("required", "true");
      $("#cd_ac_camiseta").attr("required", "true");
  }
  else {  // unchecked
      $("#cd_ac_nome").removeAttr("required");
      $("#cd_ac_nascimento").removeAttr("required");
      $("#cd_ac_genero1").removeAttr("required");
      $("#cd_ac_cpf").removeAttr("required");
      $("#cd_ac_telefone").removeAttr("required");
      $("#cd_ac_email").removeAttr("required");
      $("#dd_acompanhante").hide();
      $("#cd_ac_camiseta").removeAttr("required");
  }
}
