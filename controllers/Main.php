<?php

// Eliminación de namespaces y type hints para compatibilidad con PHP 5.3
class Main {
    public static function index($router) {
        // Para la url '/'
        $router->render('main/index', array(
            'title' => '',
            'metaTitle' => 'Página principal Rama IEEE Unicauca',
            'descriptionSite' => 'El IEEE es una organización sin ánimo de lucro y es considerada la mayor asociación del mundo para el desarrollo tecnológico.'
        ));
    }

    public static function events($router) {
        // Para la url '/eventos'
        $router->render('main/events', array(
            'title' => '- Eventos',
            'metaTitle' => 'Eventos - Rama IEEE Unicauca',
            'descriptionSite' => '¿Qué hacemos? Entérate de todos los eventos que realizamos en nuestro recorrido.'
        ));
    }

    public static function tet_2025($router) {
        // Para la url '/tet'
        $router->render('main/tet_2025', array(
            'title' => '- TET 2025',
            'metaTitle' => 'TET 2025 - Rama IEEE Unicauca',
            'descriptionSite' => 'El TET 2025 es un evento organizado por la Rama IEEE Unicauca, donde se busca fomentar el desarrollo tecnológico y la innovación en la región.'
        ));
    }

    public static function year_book($router) {
        // Para la url '/anuario'
        $router->render('main/year_book', array(
            'title' => '- Anuario',
            'metaTitle' => 'Anuario - Rama IEEE Unicauca',
            'descriptionSite' => 'El anuario es un documento que recopila las actividades y logros de la Rama IEEE Unicauca durante el año.'
        ));
    }

    public static function gallery($router) {
        // Para la url '/galeria'
        $router->render('main/galery', array(
            'title' => '- Galería',
            'metaTitle' => 'Galería - Rama IEEE Unicauca',
            'descriptionSite' => 'En nuestra galería podrás encontrar fotos y videos de nuestros eventos y actividades.'
        ));
    }

    public static function organization($router) {
        // Para la url '/organizacion'
        $router->render('main/organization', array(
            'title' => '- Organización',
            'metaTitle' => 'Organización - Rama IEEE Unicauca',
            'descriptionSite' => 'Conoce a los miembros de la Rama IEEE Unicauca y su trayectoria en el desarrollo tecnológico.'
        ));
    }
}