<?php

/*
    ROUTER
    Este archivo asigna las URLs del sitio en su correspondiente método GET o POST,
    le asigna una función para el llamado del CONTROLLER y obtiene la información del archivo VIEW,
    luego lo renderiza con el layout.
*/

namespace MVC;

class Router{
    private mixed $getRoutes = [];
    private mixed $postRoutes = [];

    public function get(string $url, array $fn) : void {
        // Asigna URL como GET y almacena.
        $this->getRoutes[$url] = $fn;
    }

    public function post(string $url, array $fn) : void {
        // Asigna URL como POST y almacena.
        $this->postRoutes[$url] = $fn;
    }

    public function checkRoutes() : void {
        //Verifica si la Ruta existe, y hace llamado a la función dentro de la clase dada.
        $current_Url = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        // Dependiendo si la solicitud es GET o POST verifica la función asignada para dicha URL
        if ( $method === 'GET' ) $fn = $this->getRoutes[$current_Url] ?? null;
        else $fn = $this->postRoutes[$current_Url] ?? null;
        
        // Hace el llamado a una función desconocida.
        if ( $fn ) call_user_func($fn, $this);
        else header('Location: /404');
    }

    public function render(string $view, array $data = []) : void{
        // Abre una vista PHP en la carpeta VIEWS, y le manda cierta información.
        foreach ($data as $key => $value) $$key = $value; 

        ob_start(); 
        include_once __DIR__ . "/views/$view.php";
        $content = ob_get_clean(); // Limpia el Buffer

        // Utilizar el Layout de acuerdo a la URL y manda la información para el Layout en VIEWS
        $current_Url = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '/';
        $tieneVarGet = strstr($current_Url, '?', true);
        if($tieneVarGet) $current_Url = $tieneVarGet;
        
        include_once __DIR__ . '/views/layout.php';
    }
}