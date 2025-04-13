<?php
namespace Controllers;
use MVC\Router;

class PrincipalPages {
    public static function index(Router $router) : void{
        updateSession();
        // Para la url  '/'
        $nameUser = $_SESSION['name'];
        $router->render('principalPages/index/index', [
            'title' => 'Consultoría en Responsabilidad Civil Médica en Colombia',
            'metaTitle' => 'Consultoría en Responsabilidad Civil Médica - JustiSalud',
            'descriptionSite' => 'Descubre nuestra consultoría especializada en Responsabilidad Civil Médica. Conoce cómo combinamos el conocimiento del Derecho y la Medicina para proteger tus derechos en el ámbito de la salud en Colombia.',

            'nameUser' => "{$nameUser}"
        ]);
    }

    public static function search(Router $router) : void{
        updateSession();
        // Para la url  '/buscador'
        $nameUser = $_SESSION['name']; // Si tiene sesión iniciada devuelve nombre de lo contrario devuelve 'Acceder'

        $router->render('principalPages/buscador', [
            'title' => 'Buscador en JustiSalud: Encuentra Información y Servicios',
            'metaTitle' => 'Busca información o servicios en JustiSalud',
            'descriptionSite' => 'Utiliza nuestro buscador para encontrar información detallada y servicios ofrecidos por JustiSalud. Encuentra lo que necesitas con facilidad.',            
            'nameUser' => "{$nameUser}"
        ]);
    }

    public static function blog(Router $router) : void{
        updateSession();
        // Para la url  '/blog'
        $nameUser = $_SESSION['name'];
        
        $router->render('principalPages/blog/blog', [
            'title' => 'Blog de Justicia y Salud: Artículos, Noticias y Consejos',
            'metaTitle' => 'Explora nuestro Blog: Información sobre Justicia y Salud en Colombia',
            'descriptionSite' => 'Descubre artículos informativos, noticias y consejos en el Blog de JustiSalud. Mantente actualizado sobre temas de Responsabilidad Civil Médica y salud en Colombia.',
            'nameUser' => "{$nameUser}"
        ]);
    }

    public static function news(Router $router) : void{
        updateSession();
        // Para la url  '/noticias'
        $nameUser = $_SESSION['name'];

        $router->render('principalPages/noticias', [
            'title' => 'Últimas Noticias de JustiSalud: Actualizaciones sobre Derecho Médico y Salud',
            'metaTitle' => 'Explora las Últimas Noticias e Información Relevante sobre Justicia y Salud en Colombia',
            'descriptionSite' => 'Descubre las últimas novedades y noticias relacionadas con el Derecho Médico, responsabilidad civil médica y otros temas de interés en JustiSalud. Mantente informado sobre la evolución del sistema de salud en Colombia.',
            
            'nameUser' => "{$nameUser}"
        ]);
    }

    public static function location(Router $router) : void{
        updateSession();
        // Para la url  '/location'
        $nameUser = $_SESSION['name'];

        $router->render('principalPages/location', [
            'title' => 'Ubicación de JustiSalud en Popayán - Cauca',
            'metaTitle' => 'Consulta nuestra Ubicación - Especialistas en Responsabilidad Civil Médica',
            'descriptionSite' => 'Encuentra la ubicación exacta de JustiSalud en Popayán, Cauca. Somos especialistas en Consultoría en Responsabilidad Civil Médica, brindando servicios legales y asesoría en el ámbito de la salud en Colombia.',

            'nameUser' => "{$nameUser}"
        ]);
    }
}