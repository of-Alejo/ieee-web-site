<?php
namespace Controllers;
use MVC\Router;

class Main {
    public static function index(Router $router) : void{
        // Para la url  '/'
        $router->render('main/index', [
            'title' => '',
            'metaTitle' => 'Página principal Rama IEEE Unicauca',
            'descriptionSite' => 'El IEEE es una organización sin ánimo de lucro y es considerada la mayor asociación del mundo para el desarrollo tecnológico.'
        ]);
    }
    public static function events(Router $router) : void{
        // Para la url  '/'
        $router->render('main/events', [
            'title' => '- Eventos',
            'metaTitle' => '',
            'descriptionSite' => ''
        ]);
    }
}