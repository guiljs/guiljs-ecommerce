<?php

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim(); //Rotas

$app->config('debug', true);

$app->get('/', function() { //Quando chamar via get a pasta raiz , execute isso

  $page = new Page(); //O construct vai criar o header
  $page->setTpl("index"); // Carrega o conteúdo
}); //O destruct do Hcode\Page vai criar o footer


$app->get('/admin', function() { //Quando chamar via get a pasta raiz , execute isso
  User::verifyLogin();
  $page = new PageAdmin(); //O construct vai criar o header
  $page->setTpl("index"); // Carrega o conteúdo
}); //O destruct do Hcode\Page vai criar o footer

$app->get('/admin/login', function() { //Quando chamar via get a pasta raiz , execute isso
  $page = new PageAdmin([
    "header"=>false,
    "footer"=>false
  ]); //O construct vai criar o header
  $page->setTpl("login"); // Carrega o conteúdo
}); //O destruct do Hcode\Page vai criar o footer



$app->post('/admin/login', function() { //Quando chamar via get a pasta raiz , execute isso
  User::login($_POST["login"],$_POST['password']);

  header("Location: /admin");
  exit;
}); //O destruct do Hcode\Page vai criar o footer

$app->get('/admin/logout', function(){
  User::logOut();

  header("Location: /admin");
  exit;
});

$app->run(); //Tudo carregado então roda.

?>
