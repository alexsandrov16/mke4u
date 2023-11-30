<?php

namespace Mk4U\Core;

use RuntimeException;

/**
 * FileSystem class
 * 
 * Clase para la gestion de archivos y directorios
 */
class FileSystem
{
    /**
     * Leer un fichero
     */
    public static function getFile(string $filename): string
    {
        self::hasFile($filename);

        return file_get_contents($filename);
    }

    /**
     * Verificar si el fichero existe, ademas si es legible y escribible
     */
    public static function hasFile(string $filename): void
    {
        if (!is_readable($filename) && !is_writable($filename)) {
            throw new RuntimeException(sprintf("%s no es un archivo valido", $filename));
        }
    }

    /**
     * Obtiene datos de un JSON
     */
    public static function getJson(string $json, bool $assoc = false): array|object
    {
        return json_decode(self::getFile($json), $assoc);
    }
}
