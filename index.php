<?php

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;

$app = new Slim(); //Rotas

$app->config('debug', true);

$app->get('/', function() { //Quando chamar via get a pasta raiz , execute isso 

  $page = new Page(); //O construct vai criar o header
  $page->setTpl("index"); // Carrega o conteúdo
}); //O destruct do Hcode\Page vai criar o footer

$app->run(); //Tudo carregado então roda.

?>
