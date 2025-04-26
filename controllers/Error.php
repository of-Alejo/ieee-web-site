<?php
namespace Controllers;
use MVC\Router;

class Error {
    public static function e404(Router $router) : void{
        // Para la url  '/'
        $router->render('error/e404', [
            'title' => 'Error 404',
            'metaTitle' => 'Página no encontrada',
            'descriptionSite' => 'La página que buscas no existe o ha sido eliminada.'
        ]);
    }
    public static function e500(Router $router) : void{
        // Para la url  '/'
        $router->render('error/e500', [
            'title' => 'Error 500',
            'metaTitle' => 'Error interno del servidor',
            'descriptionSite' => 'Error interno del servidor. Por favor, inténtalo más tarde.'
        ]);
    }
}