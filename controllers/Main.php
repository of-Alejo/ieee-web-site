<?php
namespace Controllers;
use MVC\Router;

class Main {
    public static function index(Router $router) : void{
        // Para la url  '/'
        $router->render('main/index', [
            'title' => '',
            'metaTitle' => '',
            'descriptionSite' => '',
        ]);
    }
}