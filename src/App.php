<?php

namespace Mk4U;

use Mk4U\Command\Cli;
use Mk4U\Core\Container;
use Mk4U\Core\Error;
use Mk4U\Core\FileSystem;
use Mk4U\Core\Routes;
use Mk4U\Http\Exceptions\HttpExceptions;
use Mk4U\Router\Router;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Mk4U App class
 */
final class App
{
    public const VERSION = '0.4 alpha';
    private const sysCFG = STORAGE . 'system.json';
    private bool $debug;
    private string $i18n;
    private bool $secure;
    private bool $logs;

    public function __construct()
    {
        //Inicializar buffer
        $this->buffering();

        //Cargar configuracion de la aplicacion
        $this->loadConfig();
    }

    /**
     * Debugar App\Mk4U
     */
    public function __debugInfo(): array
    {
        return [
            //'site'      => $this->site(),
            'urls'      => url(),
            'languages' => $this->i18n,
            'secure'   => $this->secure,
            'debug'   => $this->debug,
            'logs'     => $this->logs,
            'version'   => self::VERSION,
        ];
    }

    /**
     * Ejecuta la web app
     */
    public function run(): void
    {
        $this->handlerError();

        //implementacion del enrutador
        try {
            $content = Router::resolve(services('routes'));
        } catch (HttpExceptions $e) {
            $content = $e->render();
        }

        $this->output($content);
    }

    /**
     * Ejecuta la cli app
     */
    public function cli(int $argc, array $argv) : void
    {
        if (PHP_SAPI == 'cli') {
            $this->handlerError(PHP_SAPI);
            $this->output(new Cli);
        }
    }

    /**
     * Manejador de errores y excepciones
     */
    protected function handlerError(?string $env = null): void
    {
        //verificar Server Interface (SAPI)
        if (empty($env)) {
            $handler = $this->debug ? PrettyPageHandler::class : Error::class;
        } else {
            $handler = PlainTextHandler::class;
        }

        //verificar nivel buffer
        if (ob_get_level()) ob_clean();

        $whoops = new Run;
        $whoops->pushHandler(new $handler);
        $whoops->register();
    }

    /**
     * Carga la configuracion de storage/system.json
     */
    protected function loadConfig(): void
    {

        $sys = FileSystem::getJson(self::sysCFG);

        //Establece variables de configuracion
        $this->debug = $sys->debug;
        $this->i18n = $sys->lang;
        $this->secure = $sys->force_ssl;
        $this->logs = $sys->logs;

        //Establecer zona horaria
        date_default_timezone_set($sys->timezone);

        //Carga Contenedor
        Container::init([
            'routes' => Routes::class
        ]);
    }

    /**
     * Activar almacenamiento en el buffer
     */
    private function buffering(): void
    {
        ob_start();
    }

    /**
     * Vaciar buffer
     */
    private function output(mixed $content): void
    {
        if (ob_get_length() != 0) {
            flush();
        } else {
            echo $content;
        }

        ob_end_flush();
    }
}
