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

    $page->setTpl("cart", [
        'cart' => $cart->getValues(),
        'products' => $cart->getProducts(),
        'error' => Cart::getMsgError()
    ]);
});

$app->get('/cart/:idproduct/add', function ($idproduct) {
    $product = new Product();

    $product->get((int) $idproduct);
    $cart = Cart::getFromSession();

    $qtd = (isset($_GET['qtd'])) ? $_GET['qtd'] : 1;

    for ($i = 0; $i < $qtd; $i++) {
        $cart->addProduct($product);
    }

    header("Location: /cart");
    exit;
});

$app->get('/cart/:idproduct/minus', function ($idproduct) {
    $product = new Product();

    $product->get((int) $idproduct);

    $cart = Cart::getFromSession();

    $cart->removeProduct($product);

    header("Location: /cart");
    exit;
});

$app->get('/cart/:idproduct/remove', function ($idproduct) {
    $product = new Product();

    $product->get((int) $idproduct);

    $cart = Cart::getFromSession();

    $cart->removeProduct($product, true);

    header("Location: /cart");
    exit;
});


$app->post('/cart/freight', function(){

    $cart = Cart::getFromSession();

    $cart->setFreight($_POST['zipcode']);

    header('Location: /cart');
    exit;
});