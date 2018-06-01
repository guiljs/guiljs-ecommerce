<?php

use \Hcode\Model\Category;
use \Hcode\Page;

$app->get('/', function () { //Quando chamar via get a pasta raiz , execute isso

    $page = new Page(); //O construct vai criar o header
    $page->setTpl("index"); // Carrega o conteúdo
}); //O destruct do Hcode\Page vai criar o footer

$app->get("/categories/:id", function ($id) {
    $category = new Category();

    $category->get((int) $id);
    $page = new Page();
    $page->setTpl("category", array(
        "category" => $category->getValues(),
        "products" => []
    ));
});
