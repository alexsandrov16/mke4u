<?php

namespace Mk4U\Core;

use Mk4U\Http\Request;
use Mk4U\Http\Response;
use Mk4U\Router\RouteCollection;

/**
 * Routes class
 */
class Routes extends RouteCollection
{
    public function __construct()
    {
        $this->get('/', function (Request $request,Response $response) {
            return $response::json(['message'=>'🥳 Welcome to Mk4U']);
        });

        return parent::class;
    }

}
