<?php

namespace Mk4U\Http;

use InvalidArgumentException;

/**
 * Uri class
 */
class Uri
{
    protected string $scheme = '';
    protected string $host = '';
    protected ?int $port = NULL;
    protected string $path = '';
    protected string $query = '';
    protected string $fragment = '';

    private const  DEFAULT_PORT = [
        'http'  => 80,
        'https' => 443,
    ];

    public function __construct(string $uri = '')
    {
        if (!empty($uri)) {

            if (!filter_var($uri, FILTER_VALIDATE_URL)) {
                //Unable to parse URI
                throw new InvalidArgumentException("No se puede analizar la URI");
            }
            $parts = parse_url($uri);

            $this->setScheme(isset($parts['scheme']) ? $parts['scheme'] : '')
                ->setHost(isset($parts['host']) ? $parts['host'] : '')
                ->setPort(isset($parts['port']) ? $parts['port'] : NULL)
                ->setPath(isset($parts['path']) ? $parts['path'] : '')
                ->setQuery(isset($parts['query']) ? $parts['query'] : '')
                ->setFragment(isset($parts['fragment']) ? $parts['fragment'] : '');
        }
    }

    /** 
     * Establece el esquema de la url
     */
    public function setScheme(string $scheme = ''): static
    {
        if (preg_match('/\d/', $scheme)) throw new \InvalidArgumentException(sprintf("Esquemas no compatibles; %s", $scheme));

        $this->scheme = empty($scheme) ? 'http' : $scheme;

        return $this;
    }

    /** 
     * Establece el host de la url
     */
    public function setHost(string $host = ''): static
    {
        if (strpos($host, ':') != false) {
            $host = explode(':',$host)[0];
        }
        $this->host = $host;
        return $this;
    }

    /** 
     * Establece puerto
     */
    public function setPort(?int $port = NULL): static
    {
        if (isset($port) && $port <= 0 || $port > 65535) {
            throw new \InvalidArgumentException(sprintf('Puerto no válido: %d. Debe estar entre 0 y 65535', $port));
        }
        $this->port = $port;

        return $this;
    }

    /** 
     * Establece la ruta de la url
     * 
     * Si la ruta contiene parametros de consulta los envia a setQuery()
     */
    public function setPath(string $path = '/'): static
    {
        $arr = explode('?', $path);

        if (isset($arr[1])) {
            $this->setQuery($arr[1]);
        }

        $this->path = $arr[0];

        return $this;
    }
    /** 
     * Establece las consultas de la url
     */
    public function setQuery(string $query = ''): static
    {
        $this->query = ltrim($query, '?');;
        return $this;
    }

    /** 
     * Establece el fragmento de URI especificado
     */
    public function setFragment(string $fragment = ''): static
    {
        $this->fragment = ltrim($fragment, '#');
        return $this;
    }

    /** 
     * Recuperar el componente de esquema de la URI.
     * 
     * @see https://tools.ietf.org/html/rfc3986#section-3.1 
     */
    public function getScheme(): string
    {
        return $this->normalize($this->scheme);
    }

    /** 
     * Recuperar el componente host del URI.
     * 
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2 
     */
    public function getHost(): string
    {
        return $this->normalize($this->host);
    }


    /** 
     * Recuperar el componente de puerto de la URI.
     */
    public  function  getPort(): ?int
    {
        if ($this->getScheme() == '' && isset($this->port)) {
            return null;
        }
        foreach (self::DEFAULT_PORT as $key => $value) {
            if ($key === $this->getScheme() && $value === $this->port) {
                return null;
            }
        }
        return $this->port;
    }

    /** 
     * Recuperar el componente de ruta del URI.
     * 
     * @see https://tools.ietf.org/html/rfc3986#section-2 
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     */
    public  function  getPath(): string
    {
        return $this->path;
    }

    /** 
     * Recuperar la cadena de consulta de la URI.
     * 
     * @see https://tools.ietf.org/html/rfc3986#section-2 
     * @see https://tools.ietf.org/html/rfc3986#section-3.4 
     */
    public  function  getQuery(): string
    {
        return $this->query;
    }

    /** 
     * Recuperar el componente de fragmento de la URI.
     * 
     * @see https://tools.ietf.org/html/rfc3986#section-2 
     * @see https://tools.ietf.org/html/rfc3986#section-3.5 
     */
    public  function  getFragment(): string
    {
        return $this->fragment;
    }

    /** 
     * Devuelve la representación de la URI como texto. 
     * 
     * @see http://tools.ietf.org/html/rfc3986#section-4.1 
     */
    public function __toString(): string
    {
        $uri = '';

        if (!empty($this->getScheme())) $uri .= $this->getScheme() . ':';

        if (!empty($this->getHost())) {
            $host = '//' . $this->getHost();
            $uri .= empty($this->getPort()) ? $host : "$host:" . $this->getPort();
        }

        $uri .= $this->getPath();
        if (!empty($this->getQuery())) $uri .= '?' . $this->getQuery();
        if (!empty($this->getFragment())) $uri .= '#' . $this->getFragment();

        return $uri;
    }

    /**
     * Normalizar a minusculas cadena de caracteres
     */
    private function normalize(string $var): string
    {
        return strtolower($var);
    }

    /**
     * Verificar query
     */
    public function hasQuery(): bool
    {
        return empty($this->query);
    }

    /**
     * Verificar fragment
     */
    public function hasFragment(): bool
    {
        return empty($this->fragment);
    }
}
