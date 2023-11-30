<?php

namespace Mk4U\Core;

/**
 * Paths class
 * 
 * Especifica las rutas dentro del directorio storage
 */
class Paths
{
    /** @const string backup ruta del directorio backup*/
    public const backup = STORAGE . 'backups' . DS;

    /** @const string cache ruta del directorio cache*/
    public const cache = STORAGE . 'cache' . DS;

    /** @const string data ruta del directorio data*/
    public const data = STORAGE . 'data' . DS;

    /** @const string i18n ruta del directorio i18n*/
    public const i18n = STORAGE . 'i18n' . DS;

    /** @const string logs ruta del directorio logs*/
    public const logs = STORAGE . 'logs' . DS;

    /** @const string media ruta del directorio media*/
    public const media = STORAGE . 'media' . DS;

    /** @const string pages ruta del directorio pages*/
    public const pages = STORAGE . 'pages' . DS;

    /** @var string $plugins ruta del directorio plugins*/
    public const plugins = STORAGE . 'plugins' . DS;

    /** @const string themes ruta del directorio themes*/
    public const themes = STORAGE . 'themes' . DS;

    /** @const string users ruta del directorio users*/
    public const users = STORAGE . 'users' . DS;

    /**
     * Ruta del directorio ../public
     */
    public static function publicPath(): string
    {
        return dirname(STORAGE) . DS . 'public' . DS;
    }
}
