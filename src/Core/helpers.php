<?php

use Mk4U\Core\Container;
use Mk4U\Http\Request;

//Define la ruta de storage/ para las constantes de Mk4U\Core\Path
define('DS', DIRECTORY_SEPARATOR);
define('STORAGE', dirname(__DIR__, 2) . DS . 'storage' . DS);

if (!function_exists('services')) {
    /**
     * Carga el servicio solicitado
     * 
     * Inyector de dependencias basico de mk4u
     * 
     * @param string $service id del servicio solicitado
     * @param $params parametros para la instanciacion del servicio
     */
    function services(string $service, ...$params): object
    {
        return (Container::init())->get($service, $params);
    }
}

if (!file_exists('url')) {
    /**
     * Establecer url base
     * 
     * Si se le pasa un path lo agrega al final de la url base, en caso de 
     * pasarle otra url devuelve dicha url
     */
    function url(string $url = ''): string
    {
        $request = new Request;
        $base = $request->getUri()->getAuthority();
        
        // Agrega las path a la url base
        $search = dirname($request->server('SCRIPT_NAME'));
        $url = ltrim($url, '/');

        //Devuelve url pasada
        if (preg_match("~^https?://~i", $url)) return $url;

        if (stripos(rtrim($request->getTarget(), '/'), $search) !== false) {

            //Devuelve url base
            if (empty($url)) return $base.$search;

            return $base . $search . '/' . $url;
        }
        return "$base/$url";
    }
}
