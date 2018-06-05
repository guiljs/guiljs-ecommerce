<?php

use \Hcode\Model\Category;
use \Hcode\Page;
use \Hcode\Model\Product;

$app->get('/', function () { //Quando chamar via get a pasta raiz , execute isso

  $products = Product::listAll();
  $page = new Page(); //O construct vai criar o header
  $page->setTpl("index", [
    "products"=>Product::checkList($products)
  ]); // Carrega o conteúdo

}); //O destruct do Hcode\Page vai criar o footer

$app->get("/categories/:id", function ($id) {

  $currentPage = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

  $category = new Category();

  $category->get((int) $id);

  $pagination = $category->getProductsPage($currentPage);

$pages = [];
for ($i=1; $i < $pagination['pages']; $i++) {
  array_push($pages,[
    'link'=>'/categories/'.$category->getidcategory().'?page='.$i,
    'page'=>$i
  ]);
}

  $page = new Page();

  $page->setTpl("category", array(
    "category" => $category->getValues(),
    "products" => $pagination['data'],
    'pages'=>$pages
  ));
});
