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
            'metaTitle' => 'Eventos - Rama IEEE Unicauca',
            'descriptionSite' => '¿Qué hacemos? Entérate de todos los eventos que realizamos en nuestro recorrido.'
        ]);
    }
    public static function tet_2025(Router $router) : void{
        // Para la url  '/'
        $router->render('main/tet_2025', [
            'title' => '- TET 2025',
            'metaTitle' => 'TET 2025 - Rama IEEE Unicauca',
            'descriptionSite' => 'El TET 2025 es un evento organizado por la Rama IEEE Unicauca, donde se busca fomentar el desarrollo tecnológico y la innovación en la región.'
        ]);
    }
    public static function year_book(Router $router) : void{
        // Para la url  '/'
        $router->render('main/year_book', [
            'title' => '- TET 2025',
            'metaTitle' => 'TET 2025 - Rama IEEE Unicauca',
            'descriptionSite' => 'El TET 2025 es un evento organizado por la Rama IEEE Unicauca, donde se busca fomentar el desarrollo tecnológico y la innovación en la región.'
        ]);
    }
    public static function galery(Router $router) : void{
        // Para la url  '/'
        $router->render('main/galery', [
            'title' => '- TET 2025',
            'metaTitle' => 'TET 2025 - Rama IEEE Unicauca',
            'descriptionSite' => 'El TET 2025 es un evento organizado por la Rama IEEE Unicauca, donde se busca fomentar el desarrollo tecnológico y la innovación en la región.'
        ]);
    }
    public static function organization(Router $router) : void{
        // Para la url  '/'
        $router->render('main/organization', [
            'title' => '- TET 2025',
            'metaTitle' => 'TET 2025 - Rama IEEE Unicauca',
            'descriptionSite' => 'El TET 2025 es un evento organizado por la Rama IEEE Unicauca, donde se busca fomentar el desarrollo tecnológico y la innovación en la región.'
        ]);
    }
}