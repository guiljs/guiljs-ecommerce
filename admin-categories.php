<?php

use \Hcode\Model\Category;
use \Hcode\Model\Product;
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

$app->get("/admin/categories/:idcategory/products", function($id){
  User::verifyLogin();
  $category = new Category();

  $category->get((int) $id);
  $page = new PageAdmin();
  $page->setTpl("categories-products", array(
    "category" => $category->getValues(),
    "productsRelated" => $category->getProducts(),
    "productsNotRelated" => $category->getProducts(false)
  ));

});

$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory,$idproduct){
  User::verifyLogin();

  $category = new Category();

  $category->get((int)$idcategory);

  $product = new Product();

  $product->get((int)$idproduct);

  $category->addProduct($product);

  header("Location: /admin/categories/".$idcategory."/products");
  exit;
});

$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory,$idproduct){
  User::verifyLogin();

  $category = new Category();

  $category->get((int)$idcategory);

  $product = new Product();

  $product->get((int)$idproduct);

  $category->removeProduct($product);

  header("Location: /admin/categories/".$idcategory."/products");
  exit;
});
