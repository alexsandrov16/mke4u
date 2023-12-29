<?php

namespace Mk4U\Core;

use Mk4U\Http\Exceptions\HttpExceptions;
use Mk4U\Http\HttpStatus;
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
     * Devuelve plantilla de error personalizada
     */
    public function handle(): ?int
    {
        $e = new HttpExceptions(HttpStatus::InternalServerError);
        echo $e->render();

        return Handler::QUIT;
    }
}
