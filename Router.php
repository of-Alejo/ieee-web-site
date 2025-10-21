<?php

/*
    ROUTER
    Este archivo asigna las URLs del sitio en su correspondiente método GET o POST,
    le asigna una función para el llamado del CONTROLLER y obtiene la información del archivo VIEW,
    luego lo renderiza con el layout.
*/

class Router {
    private $getRoutes;
    private $postRoutes;

    public function __construct() {
        $this->getRoutes = array();
        $this->postRoutes = array();
    }

    public function get($url, $fn) {
        // Asigna URL como GET y almacena.
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn) {
        // Asigna URL como POST y almacena.
        $this->postRoutes[$url] = $fn;
    }

    public function checkRoutes() {
        // Verifica si la Ruta existe, y hace llamado a la función dentro de la clase dada.
        $current_Url = strtok(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/', '?');
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

        if (strlen($current_Url) > 100) {
            header('Location: /404');
            exit();
        }

        if (preg_match('/[<>"\']/', $current_Url)) {
            header('Location: /404'); 
            exit();
        }

        if ($method === 'GET') {
            $fn = isset($this->getRoutes[$current_Url]) ? $this->getRoutes[$current_Url] : null;
        } else {
            $fn = isset($this->postRoutes[$current_Url]) ? $this->postRoutes[$current_Url] : null;
        }

        // Hace el llamado a una función desconocida.
        if ($fn) {
            call_user_func($fn, $this);
        } else {
            header('Location: /404');
        }
    }

    public function render($view, $data = array()) {
        // Abre una vista PHP en la carpeta VIEWS, y le manda cierta información.
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once __DIR__ . "/views/$view.php";
        $content = ob_get_clean(); // Limpia el Buffer

        // Utilizar el Layout de acuerdo a la URL y manda la información para el Layout en VIEWS
        $current_Url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/');
        $tieneVarGet = strstr($current_Url, '?', true);
        if ($tieneVarGet) {
            $current_Url = $tieneVarGet;
        }

        include_once __DIR__ . '/views/layout.php';
    }
}