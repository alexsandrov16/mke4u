<?php

namespace Mk4U\Core;

use InvalidArgumentException;
use Mk4U\Traits\Singleton;
use ReflectionClass;
use ReflectionMethod;

/**
 * Container class
 */
class Container
{
    use Singleton;

    private function __construct(private array $services)
    {
        $this->services = $services;
    }

    /**
     * Devuelve el servico
     */
    public function get(string $id, mixed ...$param): object
    {
        $this->has($id);

        return $this->resolve($id, $param);
    }

    /**
     * Verifica si no esta definido el servicio
     * 
     * Si el servicio no esta definido lanza un InvalidArgumentException
     */
    private function has(string $id): void
    {
        if (!key_exists($id, $this->services)) {
            throw new InvalidArgumentException(sprintf("No se encontro el servicio %s en el archivo de configuración", $id));
        }
    }

    /**
     * Resuelve e instancia el servico
     */
    private function resolve(string $id, ...$params): object
    {
        $rf = new ReflectionClass($this->services[$id]);

        $cMethod = $rf->getConstructor();

        if (!($cMethod instanceof ReflectionMethod) || empty($cMethod->getParameters())) {
            return $rf->newInstance();
        } else {
            return $rf->newInstance($params);
        }
    }
}
