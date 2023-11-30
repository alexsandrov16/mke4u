<?php

namespace Mk4U\Core;

use Mk4U\Http\Response;
use Whoops\Handler\Handler;

/**
 * Error class
 * 
 * Esta clase renderiza una plantilla de error 500 predeterminada.
 * 
 * Modificar esta clase en un futuro para que permita intercambiar entre la plantilla predeterminada 
 * u otra establecida por el usuario en el tema instalado.
 */
class Error extends Handler
{
    /**
     * Devuelve
     */
    public function handle(): ?int
    {
        echo Response::html($this->html(), 500);
        return Handler::QUIT;
    }

    /**
     * Plantilla de error 500 predeterminada
     */
    public function html(): string
    {
        return <<<HTML
        <html>
        <head>
            <title>Server Error</title>
            <style>*{margin:0;padding:0;}body{height:80vh;display:flex;justify-content:center;align-items:center;font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;color: #535353;background-color: #e7e7e7af;}h1{font-size:3.25em;font-weight:400;}</style>
        </head>
        <body>
            <h1>Error 500</h1>
        </body>
        </html>
        HTML;
    }
}
