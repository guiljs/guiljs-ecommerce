<?php

session_start();

require_once "vendor/autoload.php";

use \Hcode\Model\User;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Slim\Slim;

$app = new Slim(); //Rotas

$app->config('debug', true);

$app->get('/', function () { //Quando chamar via get a pasta raiz , execute isso

    $page = new Page(); //O construct vai criar o header
    $page->setTpl("index"); // Carrega o conteúdo
}); //O destruct do Hcode\Page vai criar o footer

$app->get('/admin', function () { //Quando chamar via get a pasta raiz , execute isso
    User::verifyLogin();
    $page = new PageAdmin(); //O construct vai criar o header
    $page->setTpl("index"); // Carrega o conteúdo
}); //O destruct do Hcode\Page vai criar o footer

$app->get('/admin/login', function () { //Quando chamar via get a pasta raiz , execute isso
    $page = new PageAdmin([
        "header" => false,
        "footer" => false,
    ]); //O construct vai criar o header
    $page->setTpl("login"); // Carrega o conteúdo
}); //O destruct do Hcode\Page vai criar o footer

$app->post('/admin/login', function () { //Quando chamar via get a pasta raiz , execute isso
    User::login($_POST["login"], $_POST['password']);

    header("Location: /admin");
    exit;
}); //O destruct do Hcode\Page vai criar o footer

$app->get('/admin/logout', function () {
    User::logOut();

    header("Location: /admin");
    exit;
});

$app->get('/admin/users', function () {
    User::verifyLogin();

    $users = User::listAll();

    $page = new PageAdmin();
    $page->setTpl("users", array(
        "users" => $users,
    ));
});

$app->get("/admin/users/create", function () {

    User::verifyLogin();

    $page = new PageAdmin();

    $page->settpl("users-create");
});

$app->get("/admin/users/:iduser/delete", function ($iduser) {
    User::verifyLogin();

    $user = new User();

    $user->get((int)$iduser);

    $user->delete();
    header("Location: /admin/users");
    exit;
});

$app->get("/admin/users/:iduser", function ($iduser) {
    User::verifyLogin();

    $user = new User();

    $user->get((int) $iduser);

    $page = new PageAdmin();
    $page->setTpl("users-update", array(
        "user" => $user->getValues(),
    ));
});

$app->post("/admin/users/create", function () {
    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $user->setData($_POST);
    $user->save();
    header("Location: /admin/users");
    exit;
});

$app->post("/admin/users/:iduser", function ($iduser) {
    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $user->get((int)$iduser);

    $user->setData($_POST);
    $user->update();

    header("Location: /admin/users");
    exit;
});

$app->run(); //Tudo carregado então roda.
