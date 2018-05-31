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

    $forgot = new User();

    $forgot->get((int) $iduser);

    $forgot->delete();
    header("Location: /admin/users");
    exit;
});

$app->get("/admin/users/:iduser", function ($iduser) {
    User::verifyLogin();

    $forgot = new User();

    $forgot->get((int) $iduser);

    $page = new PageAdmin();
    $page->setTpl("users-update", array(
        "user" => $forgot->getValues(),
    ));
});

$app->post("/admin/users/create", function () {
    User::verifyLogin();

    $forgot = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $forgot->setData($_POST);
    $forgot->save();
    header("Location: /admin/users");
    exit;
});

$app->post("/admin/users/:iduser", function ($iduser) {
    User::verifyLogin();

    $forgot = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $forgot->get((int) $iduser);

    $forgot->setData($_POST);
    $forgot->update();

    header("Location: /admin/users");
    exit;
});

$app->get("/admin/forgot", function () {
    $page = new PageAdmin([
        "header" => false,
        "footer" => false,
    ]);

    $page->setTpl("forgot");
});

$app->post("/admin/forgot", function () {

    User::getForgot($_POST["email"]);

    header("Location: /admin/forgot/sent");
    exit;
});

$app->get("/admin/forgot/sent", function () {

    $page = new PageAdmin([
        "header" => false,
        "footer" => false,
    ]);

    $page->setTpl("forgot-sent");

});

$app->get("/admin/forgot/reset", function () {
    $forgot = User::validForgotDecrypt($_GET["code"]);

    $page = new PageAdmin([
        "header" => false,
        "footer" => false,
    ]);

    $page->setTpl("forgot-reset", array(
        "name" => $forgot["desperson"],
        "code" => $_GET["code"],
    ));
});

$app->post("/admin/forgot/reset", function () {

    $forgot = User::validForgotDecrypt($_POST["code"]);

    User::setForgotUsed($forgot["idrecovery"]);

    $user = new User();

    $user->get((int) $forgot["iduser"]);

    $password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
        "cost" => 12,
    ]);

    $user->setPassword($password);

    $page = new PageAdmin([
        "header" => false,
        "footer" => false,
    ]);

    $page->setTpl("forgot-reset-success");
});

$app->run(); //Tudo carregado então roda.
