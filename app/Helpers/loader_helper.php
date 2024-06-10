<?php

function view_site($view,$dados=array()){
  echo view("site/header", $dados);
  echo view("site/menu", $dados);
  echo view("$view", $dados);
  echo view("site/rodape", $dados);
  echo view("site/footer", $dados);
}

function view_adm($view, $dados=array()){
  echo view("adm/header", $dados);
  echo view("adm/menu", $dados);
  echo view("$view", $dados);
  echo view("adm/footer", $dados);
}