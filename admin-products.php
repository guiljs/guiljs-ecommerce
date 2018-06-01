<?php

use \Hcode\Model\Product;
use \Hcode\Model\User;
use \Hcode\PageAdmin;

$app->get("/admin/products", function () {
    User::verifyLogin();

    $products = Product::listAll();

    $page = new PageAdmin();

    $page->setTpl("products", [
        "products" => $products,
    ]);
});

$app->get("/admin/products/create", function () {
    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("products-create");
});

$app->post("/admin/products/create", function () {
    User::verifyLogin();

    $product = new Product();
    $product->setData($_POST);
    // $product->setidproduct(0);
    $product->save();

    header("Location: /admin/products");
    exit;
});

$app->get("/admin/products/:id", function ($id) {
    User::verifyLogin();

    $product = new Product();
    $product->get((int) $id);

    $page = new PageAdmin();
    $page->setTpl("products-update", [
        "product" => $product->getValues(),

    ]);
});

$app->post("/admin/products/:id", function ($id) {
    User::verifyLogin();

    $product = new Product();
    $product->get((int) $id);

    $product->setData($_POST);

    $product->save();

    $product->setPhoto($_FILES["file"]);

    header("Location: /admin/products");

    exit;

});

$app->get("/admin/products/:id/delete", function($id){
    User::verifyLogin();

    $product = new Product();
    $product->get((int)$id);
    $product->delete();

    header("Location: /admin/products");
    exit;
});
