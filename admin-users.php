<?php

use \Hcode\Model\User;
use \Hcode\PageAdmin;

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
