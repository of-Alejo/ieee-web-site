<?php
namespace Controllers;
use MVC\Router;

class PrincipalPages {
    public static function index(Router $router) : void{
        // Para la url  '/'
        $nameUser = $_SESSION['name'];
        $router->render('principalPages/index/index', [
            'title' => '',
            'metaTitle' => '',
            'descriptionSite' => '',
        ]);
    }
}