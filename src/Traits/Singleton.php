<?php

namespace Mk4U\Traits;

/**
 * Singleton
 */
trait Singleton
{
    /** @var object $instance almacena la instancia de la clase a ser llamada */
    private static $instance;

    /**
     * Inicializala instancia de una clase
     *
     * @param Array $parms parametros de la clase a la que se esta instanciando
     * @return object
     **/
    public static function init(...$parms): object
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self(...$parms);
        }

        return self::$instance;
    }
}
