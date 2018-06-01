<?php

use \Hcode\Model\Category;
use \Hcode\Model\User;
use \Hcode\PageAdmin;

$app->get("/admin/categories", function () {
    User::verifyLogin();

    $categories = Category::listAll();

    $page = new PageAdmin();

    $page->setTpl("categories", array(
        "categories" => $categories,
    ));
});

$app->get("/admin/categories/create", function () {
    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("categories-create");
});

$app->post("/admin/categories/create", function () {
    User::verifyLogin();

    $category = new Category();

    $category->setData($_POST);
    $category->setidcategory(0); //Para criar um novo.
    $category->save();

    header("Location: /admin/categories");
    exit;

});

$app->get("/admin/categories/:id", function ($id) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int) $id);

    $page = new PageAdmin();
    $page->setTpl("categories-update", array(
        "category" => $category->getValues(),
    ));
});

$app->post("/admin/categories/:id", function ($id) {
    User::verifyLogin();

    $category = new Category();
    $category->setData($_POST);
    $category->setidcategory($id);
    $category->save();

    header("Location: /admin/categories");
    exit;

});

$app->get("/admin/categories/:id/delete", function ($id) {
    User::verifyLogin();

    $category = new Category();

    $category->get($id);

    $category->delete();

    header("Location: /admin/categories");
    exit;
});