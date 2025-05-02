<?php

// Eliminación de namespaces y type hints para compatibilidad con PHP 5.3
class AppError {
    public static function e404($router) {
        // Para la url '/404'
        $router->render('error/e404', array(
            'title' => 'Error 404',
            'metaTitle' => 'Página no encontrada',
            'descriptionSite' => 'La página que buscas no existe o ha sido eliminada.'
        ));
    }

    public static function e500($router) {
        // Para la url '/500'
        $router->render('error/e500', array(
            'title' => 'Error 500',
            'metaTitle' => 'Error interno del servidor',
            'descriptionSite' => 'Error interno del servidor. Por favor, inténtalo más tarde.'
        ));
    }
}