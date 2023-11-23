<?php

namespace Mk4U\Router;

/**
 * RouteCollection class
 * 
 * Encargada de crear y recolectar las rutas
 */
class RouteCollection
{
    /* @param array $routes Almacena todas las rutas */
    private array $routes = [];

    /* @param string $group Almacena el grupo de la ruta */
    private ?string $group = null;

    /* @param string $namespace Almacena el namespaces del action */
    private ?string $namespace = null;

    /**
     * Almacena las rutas
     */
    private function add(array|string $method, string $path, array|callable $action): void
    {
        if (key_exists($this->group . $path, $this->all())) {
            throw new \InvalidArgumentException(sprintf('"%s" ya fue definida con anterioridad', $path));
        }

        if (is_array($action)) {
            $action[0] = $this->namespace . $action[0];
        }

        $this->routes[$this->group . $path] = [
            'method' => $method,
            'action' => $action,
            //'option' => ['before','after']
        ];
    }

    /**
     * Establece las rutas mediante el metodo GET
     */
    public function get(string $path, array|callable $action): static
    {
        $this->add('GET', $path, $action);
        return $this;
    }

    /**
     * Establece las rutas mediante el metodo POST
     */
    public function post(string $path, array|callable $action): static
    {
        $this->add('POST', $path, $action);
        return $this;
    }

    /**
     * Establece las rutas mediante el metodo PUT
     */
    public function put(string $path, array|callable $action): static
    {
        $this->add('POST', $path, $action);
        return $this;
    }

    /**
     * Establece las rutas mediante el metodo DELETE
     */
    public function delete(string $path, array|callable $action): static
    {
        $this->add('POST', $path, $action);
        return $this;
    }

    /**
     * Establece las rutas con varios metodos HTTP
     **/
    public function map(array $methods, string $path, array|callable $action): static
    {
        //Pasar a mayuscula los metodos
        $methods = array_map('strtoupper', $methods);
        $this->add($methods, $path, $action);

        return $this;
    }

    /**
     * Establece las rutas en grupos
     */
    public function group(string $path, callable $callbak): static
    {
        $this->group = $path;
        $callbak($this);
        $this->group = null;

        return $this;
    }

    public function namespace(string $namespace): static
    {
        $this->namespace = $namespace . '\\';
        return $this;
    }

    /**
     * Muestra todas las rutas almacenadas
     */
    public function all(): array
    {
        return $this->routes;
    }

    /**
     * 
     */
    public function save(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Depurar con vardump
     */
    public function __debugInfo()
    {
        return $this->all();
    }
}
