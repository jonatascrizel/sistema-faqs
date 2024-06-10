<?php

function limitaTxt($txt, $limite = 10, $complemento = '...')
{
  return mb_strimwidth($txt, 0, $limite, $complemento, 'utf-8');
}

function gerarSenha($tamanho = 8, $maiusculas = false, $numeros = true, $simbolos = false)
{
  // Caracteres de cada tipo 
  $lmin = 'abcdefghijklmnopqrstuvwxyz';
  $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $num = '1234567890';
  $simb = '!@#$%*-';

  // Variáveis internas 
  $retorno = '';
  $caracteres = '';

  // Agrupamos todos os caracteres que poderão ser utilizados 
  $caracteres .= $lmin;
  if ($maiusculas)
    $caracteres .= $lmai;
  if ($numeros)
    $caracteres .= $num;
  if ($simbolos)
    $caracteres .= $simb;

  // Calculamos o total de caracteres possíveis 
  $len = strlen($caracteres);
  for ($n = 1; $n <= $tamanho; $n++) {
    // Criamos um número aleatório de 1 até $len para pegar um dos caracteres 
    $rand = mt_rand(1, $len);

    // Concatenamos um dos caracteres na variável $retorno 
    $retorno .= $caracteres[$rand - 1];
  }
  return $retorno;
}

function formatCnpjCpf($value) {
  $CPF_LENGTH = 11;
  $cnpj_cpf = preg_replace("/\D/", '', $value);
  
  if (strlen($cnpj_cpf) === $CPF_LENGTH) {
    return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
  } 
  
  return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

function limpaCnpjCpf($value){
  $remover = ['.','-','/'];

  return str_replace($remover,'',$value);
}
