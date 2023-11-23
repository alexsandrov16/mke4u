<?php

namespace Mk4U;

/**
 * Mk4U App class
 */
final class App
{
    public function __construct()
    {
        # code..
    }

    /**
     * 
     */
    public function __toString()
    {
        return 'Ready...';
    }

    /**
     * Ejecuta la web app
     */
    public function run() //: Returntype
    {
        # code...
    }

    /**
     * Ejecuta la cli app
     */
    public function cli(int $argc, array $argv) //: Returntype
    {
        if (PHP_SAPI == 'cli') {
            echo 'Mk4U CLI ready...';
        }
    }
}
