$(window).on('load', function () {

  let ClassicEditor;
  vlTotalMov = 0;


  montaMenu();

  $.getScript(caminho + '/js/jquery.mask.js', function () {
    maskAll();
  });

  $('.filtro').hide();
   	

  $.datepicker.setDefaults({
    dateFormat: "dd/mm/yy",
    dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
    nextText: 'Proximo',
    prevText: 'Anterior',
    autoSize: true,

  });

  $('.date').datepicker({
    //minDate: 0,
  });

  $('#dateRangeFrom').datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    numberOfMonths: 3
  })
  .on( "change", function() {
    $('#dateRangeTo').datepicker( "option", "minDate", this.value );
  });

  $('#dateRangeTo').datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    numberOfMonths: 3
  })
  .on( "change", function() {
    $('#dateRangeFrom').datepicker( "option", "maxDate", this.value );
  });


  // validator
  $.validator.setDefaults({
    debug: false,//impede de enviar caso for true
    errorElement: "em",
    ignore: ".ignore",
    errorPlacement: function (error, element) {
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid");//.removeClass( "is-valid" );
      $(element).parents('.form-group').addClass("text-danger");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid");//.addClass( "is-valid" )
      $(element).parents('.form-group').removeClass("text-danger");
    },
    ignore: ':disabled, [type="search"]',
  });


  //validator defaults
  brdocs = {
    /**
    * Enum com opções do validador
    * @readonly
    * @enum {number}
    */
    cpfcnpj: { "CPF": 1, "CNPJ": 2, "AMBOS": 3 },

    /**
     * Função que valida CPF e CNPJ de uma só vez.
     * O Documento a ser validado depende apenas da quantidade de dígitos
     * 11 é aceito como CPF, 14 como CNPJ..
     * @param {string} value - Número do CPF ou CNPJ a ser validado.
     * @param {Element} element - Elemento HTML onde o valor se encontra.
     * @param {Object} [params=3] params - pametros do validador definidos 
     *			pelo enum brdocs.cpfcnpj, default assume AMBOS 
     * @returns {boolean} se o documento é válido
     */
    cpfcnpjValidator: function (value, element, params) {
      //params = (typeof params === 'undefined' || (typeof params === 'boolean' && params) ) ? brdocs.cpfcnpj.AMBOS : params;
      value = value.replace(/[^\d]+/g, ''); //Remove todos os cacteres que exceto [0-9]
      var isCNPJ = false;

      if (value.length != 11 && value.length != 14) return false;

      switch (params) {
        case brdocs.cpfcnpj.CPF:
          if (value.length != 11) return false;
          isCNPJ = false;
          break;
        case brdocs.cpfcnpj.CNPJ:
          if (value.length != 14) return false;
          isCNPJ = true;
          break;
        default:
          isCNPJ = (value.length === 14)
          break;
      }

      if (/^(\d)\1+$/.test(value)) return false; //falso se se todos os digitos forem iguais, os digitos verificadores estão corretos, mas o documento não é válido.

      if (brdocs.calculaDigito(value, value.length - 3, isCNPJ) != parseInt(value.charAt(value.length - 2))) return false;
      if (brdocs.calculaDigito(value, value.length - 2, isCNPJ) != parseInt(value.charAt(value.length - 1))) return false;

      return true;
    },
    /**
    * Função que valida 1 dígito verificador, lembrando que
    * esta função não vai checar se o documento tem tamanho 
    * documento está correto, vai apenas calcular o dígito.
    * A única diferença nos algoritimos de CPF e CNPJ é que o 
    * multiplicador deve voltar a 2 quando passar de 9 no caso
    * do cnpj, ao contrário do CPF que multiplicador máximo é 
    * quantidade de caracteres no processo de soma + 2.
    *  
    * @param {string} doc - Número do documento CPF ou CNPJ a ser validado (somente números).
    * @param {number} start [start=doc.length-1] - Indice do char em doc por onde o iteração do cálculo deve iniciar 
    * 	(útil quando a string doc não foi separada previamento dos dígitos verificadores).
    * @param {boolean} [isCNPJ=false] - Se documento deve ser tratado como CPF, se omitido é tratado como falso.
    * @returns {number} valor calculado do digito.
    */
    calculaDigito: function (doc, start, isCNPJ) {
      if (doc.length === 0) return false;

      start = (typeof start === 'undefined') ? doc.length - 1 : start;

      if (start >= doc.length)
        return false;

      if (isNaN(doc))
        return false;

      isCNPJ = (typeof isCNPJ === 'undefined') ? false : isCNPJ;

      var add = 0
      var multi = 2;

      for (i = start; i >= 0; i--) {
        add += parseInt(doc.charAt(i)) * multi++
        if (isCNPJ && multi > 9) multi = 2;
      }
      var resultado = 11 - add % 11;

      return resultado < 9 ? resultado : 0;;
    }
  };
  if (Object.freeze) { Object.freeze(brdocs); }

  jQuery.validator.addMethod("cpfcnpj2", brdocs.cpfcnpjValidator, "Informe um documento válido.");

  jQuery.validator.addMethod("docCNPJ", function (value, element) {

    var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
    if (value.length == 0) {
      return false;
    }

    value = value.replace(/\D+/g, '');
    digitos_iguais = 1;

    for (i = 0; i < value.length - 1; i++)
      if (value.charAt(i) != value.charAt(i + 1)) {
        digitos_iguais = 0;
        break;
      }
    if (digitos_iguais)
      return false;

    tamanho = value.length - 2;
    numeros = value.substring(0, tamanho);
    digitos = value.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
        pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0)) {
      return false;
    }
    tamanho = tamanho + 1;
    numeros = value.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
        pos = 9;
    }

    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;

    retorno = (resultado == digitos.charAt(1));

    return this.optional(element) || retorno;
  }, "Informe um CNPJ válido")

  jQuery.validator.addMethod("cpf", function (value, element) {
    value = jQuery.trim(value);

    value = value.replace('.', '');
    value = value.replace('.', '');
    cpf = value.replace('-', '');
    while (cpf.length < 11) cpf = "0" + cpf;
    var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
    var a = [];
    var b = new Number;
    var c = 11;
    for (i = 0; i < 11; i++) {
      a[i] = cpf.charAt(i);
      if (i < 9) b += (a[i] * --c);
    }
    if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11 - x }
    b = 0;
    c = 11;
    for (y = 0; y < 10; y++) b += (a[y] * c--);
    if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11 - x; }

    var retorno = true;
    if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg)) retorno = false;

    return this.optional(element) || retorno;

  }, "Informe um CPF válido");

  //Celular
  jQuery.validator.addMethod('celular', function (value, element) {
    value = value.replace("(", "");
    value = value.replace(")", "");
    value = value.replace("-", "");
    value = value.replace(" ", "").trim();
    if (value == '0000000000') {
      return (this.optional(element) || false);
    }
    else if (value == '00000000000') {
      return (this.optional(element) || false);
    }
    if (["00", "01", "02", "03", , "04", , "05", , "06", , "07", , "08", "09", "10"].indexOf(value.substring(0, 2)) != -1) {
      return (this.optional(element) || false);
    }
    if (value.length < 10 || value.length > 11) {
      return (this.optional(element) || false);
    }
    if (["6", "7", "8", "9"].indexOf(value.substring(2, 3)) == -1) {
      return (this.optional(element) || false);
    }
    return (this.optional(element) || true);
  },
    'Informe um celular válido');

  //Telefone fixo
  jQuery.validator.addMethod('telefoneFixo', function (value, element) {
    value = value.replace("(", "");
    value = value.replace(")", "");
    value = value.replace("-", "");
    value = value.replace(" ", "").trim();
    if (value == '0000000000') {
      return (this.optional(element) || false);
    }
    else if (value == '00000000000') {
      return (this.optional(element) || false);
    }
    if (["00", "01", "02", "03", , "04", , "05", , "06", , "07", , "08", "09", "10"].indexOf(value.substring(0, 2)) != -1) {
      return (this.optional(element) || false);
    }
    if (value.length < 10 || value.length > 11) {
      return (this.optional(element) || false);
    }
    if (["1", "2", "3", "4", "5"].indexOf(value.substring(2, 3)) == -1) {
      return (this.optional(element) || false);
    }
    return (this.optional(element) || true);
  },
    'Informe um telefone válido');

  //Telefone generico
  jQuery.validator.addMethod('telefone', function (value, element) {
    value = value.replace("(", "");
    value = value.replace(")", "");
    value = value.replace("-", "");
    value = value.replace(" ", "").trim();
    if (value == '0000000000') {
      return (this.optional(element) || false);
    }
    else if (value == '00000000000') {
      return (this.optional(element) || false);
    }
    if (["00", "01", "02", "03", , "04", , "05", , "06", , "07", , "08", "09", "10"].indexOf(value.substring(0, 2)) != -1) {
      return (this.optional(element) || false);
    }
    if (value.length < 10 || value.length > 11) {
      return (this.optional(element) || false);
    }
    return (this.optional(element) || true);
  },
    'Informe um telefone válido');

  //data no formato pt-Br
  $.validator.addMethod("date", function (value, element) {
    var check = false;
    var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
    if (re.test(value)) {
      var adata = value.split('/');
      var gg = parseInt(adata[0], 10);
      var mm = parseInt(adata[1], 10);
      var aaaa = parseInt(adata[2], 10);
      var xdata = new Date(aaaa, mm - 1, gg);
      if ((xdata.getFullYear() == aaaa) && (xdata.getMonth() == mm - 1) && (xdata.getDate() == gg))
        check = true;
      else
        check = false;
    }
    else
      check = false;

    return this.optional(element) || check;
  },
    "Insira uma data válida"
  );

  $.validator.addMethod("validarUsuario", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9.]+$/.test(value);
  },
    'Informe um usuário válido.'
  );

  $.validator.addMethod("time24", function (value, element) {
    if (!/^\d{2}:\d{2}$/.test(value)) return false;
    var parts = value.split(':');
    if (parts[0] > 23 || parts[1] > 59) return false;
    return true;
  }, "Formato de hora inválido");

  $.validator.addMethod("password", function (value, element) {
    return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/i.test(value);
  }, "Senhas devem ter entre 8 e 16 caracteres incluindo letras maiúsculas, letras minúsculas e ao menos um número.");
  // FIM Validator

}); // fim onload

function maskAll() {
  $('.date').mask('00/00/0000');
  $('.phone_with_ddd').mask('(00) #0000-0000');
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
  $('.cnpj').mask('00.000.000/0000-00', { reverse: true });
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


function submitForm(idForm) {
  if ($("#" + idForm).valid()) {
    //pede pro usuario aguardar
    waitingDialog.show('Aguarde, processando', {
      dialogSize: 'lg',
      progressType: 'info'
    });
    $('#' + idForm).submit();
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Verifique os campos em vermelho.',
      text: 'Preencha todos os dados corretamente',
      confirmButtonColor: '#d33'
    });
  }
}

function getRelatorio(idForm, tipo){
  $("#formato").val(tipo);
  if ($("#" + idForm).valid()) {
    Swal.fire({
      title: 'Aguarde, processando',
      text: 'Após o download, feche essa janela no "X"',
      showCloseButton: true,
      didOpen: () => {
        Swal.showLoading()
      }
    })
    $('#' + idForm).submit();
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Verifique os campos em vermelho.',
      text: 'Preencha todos os dados corretamente',
      confirmButtonColor: '#d33'
    });
  }
}

function acionaTooltip() {
  $('[title]').tooltip();
  //console.log('Tooltip inicializado.');
}

function enviarPesquisa(tipo) {
  $.ajax({
    type: 'post',
    url: '../../../pesquisa/aEnviar/' + tipo,
    success: function (r) {
      $('#jsModalLabel').html('Enviar pesquisa de satisfação');
      $('#jsModal .modal-body').html(r);
      $('#jsModal').modal('show');
    },
  });
}
function verLinkPesquisa(id) {
  $.ajax({
    type: 'post',
    url: '../../../pesquisa/aVerLink/' + id,
    success: function (r) {
      $('#jsModalLabel').html('Enviar pesquisa de satisfação');
      $('#jsModal .modal-body').html(r);
      $('#jsModal').modal('show');
    },
  });
}


function ver_resposta(id) {
  $.ajax({
    type: 'post',
    url: '../../../pesquisa/ver/' + id,
    success: function (r) {
      $('#jsModalLabel').html('Respostas da pesquisa de satisfação');
      $('#jsModal .modal-body').html(r);
      $('#jsModal').modal('show');
    },
  });
}

function ckeditor(idElemento) {
  ClassicEditor
    .create(document.querySelector('#' + idElemento), {
      ckfinder: {
        uploadUrl: caminho + '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json',
        options: {
          resourceType: 'Images'
        },
      },
    })
    /*
    .then(editor => {
      console.log(editor);
    })
    */
    .catch(error => {
      console.error(error);
    });
}

function baixarPesquisa() {
  location.href = caminho + '/Pesquisa/downloadAll';
}

function ver_texto(id) {
  $.ajax({
    type: 'post',
    url: '../../../textos/ver/' + id,
    success: function (r) {
      $('#jsModalLabel').html('Visualização de texto');
      $('#jsModal .modal-body').html(r);
      $('#jsModal').modal('show');
    },
  });
}

function solicitarDados(tipo) {
  $.ajax({
    type: 'post',
    url: '../../../clientes/aEnviar/' + tipo,
    success: function (r) {
      $('#jsModalLabel').html('Enviar ficha cadastral');
      $('#jsModal .modal-body').html(r);
      $('#jsModal').modal('show');
    },
  });
}

function verLinkFicha(id) {
  $.ajax({
    type: 'post',
    url: '../../../clientes/aVerLink/' + id,
    success: function (r) {
      $('#jsModalLabel').html('Enviar ficha cadastral');
      $('#jsModal .modal-body').html(r);
      $('#jsModal').modal('show');
    },
  });
}

function deletar(lnk) {
  Swal.fire({
    icon: 'info',
    title: 'Está certo disto?',
    text: 'Essa operação não possui volta',
    showCancelButton: true,
    cancelButtonText: 'Manter',
    confirmButtonText: 'Continuar',
    confirmButtonColor: '#d33',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      location.href = lnk;
    }
  })
}

function dtDefaults() {
  $.extend($.fn.dataTable.defaults, {
    "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
    "pageLength": 50,
    "language": {
      "url": "../../../js/datatables.pt_br.json"
    }
  });
}


function buscaCep() {
  if ($('#cep').val() == '') {
    Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: 'Informe um CEP!',
      confirmButtonColor: '#d33'
    }).then((result) => {
      $("#cep").focus();
    });
  } else {
    waitingDialog.show('Processando. Por gentileza, aguarde.', { progressType: 'danger' });
    $.getJSON("https://viacep.com.br/ws/" + $('#cep').val() + "/json/?callback=?", function (dados) {
      waitingDialog.hide();
      if (!("erro" in dados)) {
        $("#estado option[value=" + dados.uf + "]").prop('selected', true);
        carrega_cidades(dados.uf, dados.localidade);
        $("#bairro").val(dados.bairro);
        $("#rua").val(dados.logradouro);
        $("#numero_comp").focus();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Erro!',
          text: 'Esse CEP não foi encontrado.',
          confirmButtonColor: '#d33'
        }).then((result) => {
          $("#cep").focus();
        });
      }
    });
  }
}

function carrega_cidades(destino, valor, selecionar, selecionarText) {
  if (valor == '') {
    Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: 'Selecione um estado antes!',
      confirmButtonColor: '#d33'
    });
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

function recuperaEstadoCidade(id_cidade, id_selUF, id_selCid) {
  waitingDialog.show('Processando. Por gentileza, aguarde.', { progressType: 'danger' });
  $.getJSON("../../../../Home/aGetEstado/" + id_cidade, function (dados) {
    $('#' + id_selUF).val(dados);

    carrega_cidades(id_selCid, $('#' + id_selUF).val(), id_cidade)

    waitingDialog.hide();
  });
}

function ver_manual(id) {
  $.ajax({
    type: 'post',
    url: '../../../manuais/ver/' + id,
    success: function (r) {
      $('#jsModalLabel').html('Visualização de manual');
      $('#jsModal .modal-body').html(r);
      $('#jsModal').modal('show');
    },
  });
}

function verPws(obj) {
  if ($("#" + obj).attr('type') == 'text') {
    $("#" + obj).attr('type', 'password');
    $("#btn_" + obj + ' i').removeClass("fa-eye-slash");
    $("#btn_" + obj + ' i').addClass("fa-eye");
  } else {
    $("#" + obj).attr('type', 'text');
    $("#btn_" + obj + ' i').removeClass("fa-eye");
    $("#btn_" + obj + ' i').addClass("fa-eye-slash");
  }
}

function trocaSenha() {
  console.log($('meter.mtSenha').val());
  if ($('meter.mtSenha').val() < 60) {
    Swal.fire({
      icon: 'error',
      title: 'A senha está muito fraca',
      text: 'Veja as instruções e escolha uma senha forte',
      confirmButtonColor: '#d33'
    });
  } else {
    submitForm('formCadastro');
  }
}

function montaMenu() {
  $.getJSON(caminho + "/Usuarios/aGeraMenu")
    .done(function (data) {
      if (data) {
        html = '';
        $.each(data, function (i, item) {
          html += '<li class="nav-item">';
          html += '<a class="nav-link" href="' + data[i].controller + '" title="' + data[i].title + '" data-bs-placement="right">';
          html += '<i class="' + data[i].icone + '"></i> ';
          html += data[i].nome;
          html += '</a>';
          html += '</li>';
        });

        $('#sidebarMenu ul').append(html);

        acionaTooltip();
      }
    });

}


function ver_lista(id) {
  $.ajax({
    type: 'post',
    url: '../../../movimentacoes/aVer/' + id,
    success: function (r) {
      $('#jsModalLabel').html('Detalhes da movimentação');
      $('#jsModal .modal-body').html(r);
      $('#jsModal').modal('show');
    },
  });
}

