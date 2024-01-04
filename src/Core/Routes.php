<?php

namespace Mk4U\Core;

use Mk4U\App;
use Mk4U\Http\Request;
use Mk4U\Http\Response;
use Mk4U\Router\RouteCollection;

/**
 * Routes class
 */
class Routes extends RouteCollection
{
    /**
     * Ejecuta las rutas
     */
    public function __construct()
    {
        $this->web();
        $this->api();

        return parent::class;
    }

    /**
     * Define las rutas para la API
     */
    private function api(): void
    {
        $this->group('/api', function () {

            /**
             * Tester Api routes
             */

            $this->get('/', function (Request $request, Response $response) {

                if (key_exists('name', $request->getParsedBody())) {
                    return $response::json(['message' => "🖖 Hi {$request->getParsedBody()['name']}, welcome to Mk4U API"]);
                }

                return $response::json(['message' => '🥳 Welcome to Mk4U API']);
            });

            $this->get('/me', function (Request $request, Response $response) {
                return $response::json(['message' => 'Mk4U API v'.App::VERSION]);
            });
        });
    }

    /**
     * Define las rutas para el sitio
     */
    private function web(): void
    {
        # code...
    }
}
