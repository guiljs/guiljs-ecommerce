<?php

use \Hcode\Model\Cart;
use \Hcode\Model\Category;
use \Hcode\Model\Product;
use \Hcode\Page;

$app->get('/', function () { //Quando chamar via get a pasta raiz , execute isso

    $products = Product::listAll();
    $page = new Page(); //O construct vai criar o header
    $page->setTpl("index", [
        "products" => Product::checkList($products),
    ]); // Carrega o conteúdo

}); //O destruct do Hcode\Page vai criar o footer

$app->get("/categories/:id", function ($id) {

    $currentPage = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

    $category = new Category();

    $category->get((int) $id);

    $pagination = $category->getProductsPage($currentPage);

    $pages = [];
    for ($i = 1; $i < $pagination['pages']; $i++) {
        array_push($pages, [
            'link' => '/categories/' . $category->getidcategory() . '?page=' . $i,
            'page' => $i,
        ]);
    }

    $page = new Page();

    $page->setTpl("category", array(
        "category" => $category->getValues(),
        "products" => $pagination['data'],
        'pages' => $pages,
    ));
});

$app->get('/products/:desurl', function ($desurl) {
    $product = new Product();
    $product->getFromUrl($desurl);

    $page = new Page();
    $page->setTpl("product-detail", [
        'product' => $product->getValues(),
        'categories' => $product->getCategories(),
    ]);
});

$app->get('/cart', function () {

    $cart = Cart::getFromSession();

    $page = new Page();

    $page->setTpl("cart");
});
