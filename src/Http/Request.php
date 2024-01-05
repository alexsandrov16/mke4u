<?php

namespace Mk4U\Http;

/**
 * Request class
 */
class Request
{
    /** @param array datos de carga de archivos*/
    private array $files;

    /** @param string solicitud de destino de la peticion Http*/
    private string $target;

    /** @param string Metodo HTTP*/
    private string $method;

    /** @param Uri instancia de la clase Mk4u\Http\Uri */
    private Uri $uri;

    /** @param array datos pasados por formulario(POST) */
    private array $content_type = ['application/x-www-form-urlencoded', 'multipart/form-data'];

    use Message;

    public function __construct()
    {
        //metodo
        $this->setMethod($this->server('request_method'));

        //version protocolo
        $this->setProtocolVersion(
            substr($this->server('server_protocol'), strrpos($this->server('server_protocol'), '/') + 1)
        );

        //ruta
        $this->setTarget($this->server('request_uri'));

        //URI
        $this->setUri(
            (new Uri())
                ->setScheme($this->server('request_scheme'))
                ->setHost($this->server('http_host'))
                ->setPort($this->server('server_port'))
                ->setPath($this->getTarget())
        );

        //Headers
        $this->headers();

        //body
        $this->setBody($this->getParsedBody());
    }

    /**
     * Debuguear mensanje de la solicuitud HTTP
     */
    public function __debugInfo(): array
    {
        return [
            "method" => $this->getMethod(),
            "target" => $this->getTarget(),
            "protocol" => "HTTP/{$this->getProtocolVersion()}",
            "headers" => $this->getHeaders(),
            "body" => $this->getBody(),
            //"file"=> IMPLEMENTAR
            "body params" => $this->getParsedBody(),
            "uri" => $this->getUri()->__toString()
        ];
    }

    /**
     * Devuelve parametros del $_SERVER.
     */
    public function server(string $index = ''): array|string
    {
        return empty($index) ? $_SERVER : (empty($_SERVER[strtoupper($index)]) ? '' : $_SERVER[strtoupper($index)]);
    }

    /**
     * Obtener cabeceras de la solicitud HTTP.
     **/
    private function headers(): static
    {
        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            foreach ($this->server() as $name => $value) {
                if (preg_match('/^HTTP_/', $name)) {
                    $headers[str_replace('HTTP_', '', $name)] = $value;
                }

                if (in_array($name, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'], true)) {
                    $headers[$name] = $value;
                }
            }
        }

        return $this->setHeaders($headers);
    }

    /**
     * Recuperar datos de carga de archivos normalizados.
     * 
     * Este método devuelve metadatos de carga en un árbol normalizado, con cada 
     * hoja una instancia de Psr\Http\Message\UploadedFileInterface.
     * 
     * Estos valores PUEDEN prepararse a partir de $_FILES o del cuerpo del mensaje 
     * durante creación de instancias, o PUEDE inyectarse a través de 
     * withUploadedFiles().
     * 
     * @return array Un árbol de matriz de instancias de UploadedFileInterface; un 
     * vacío Se DEBE devolver la matriz si no hay datos 
     */
    public function getUploadedFiles()
    {
    }

    /**
     * Crear una nueva instancia con los archivos cargados especificados.
     * 
     * Este método DEBE implementarse de tal manera que conserve la inmutabilidad 
     * del mensaje, y DEBE devolver una instancia que tenga el Parámetros corporales 
     * actualizados.
     * @param array $uploadedFiles Un árbol de matriz de instancias de 
     * UploadedFileInterface.
     * @return static
     * @throws \InvalidArgumentException si se proporciona una estructura no válida.
     */
    public function uploadedFiles(array $uploadedFiles)
    {
        
    }

    /**
     * Recuperar los parámetros proporcionados en el cuerpo de la solicitud.
     *
     * Si el tipo de contenido de la solicitud es application/x-www-form-urlencoded
     * o multipart/form-data, y el método de solicitud es POST, este método DEBE
     * devolver el contenido de $_POST.
     *
     * De lo contrario, este método puede devolver cualquier resultado de deserializar
     * el contenido del cuerpo de la solicitud; como el análisis devuelve contenido estructurado, el
     * los tipos potenciales DEBEN ser matrices u objetos solamente. Un valor nulo indica
     * la ausencia de contenido corporal.
     *
     * @return null|array|object Los parámetros del cuerpo deserializado, si los hay.
     * Por lo general, serán una matriz u objeto.
     */
    public function getParsedBody(): array|object|null
    {

        //Si el metodo es GET devolver superglobal
        if ($this->getMethod() == 'GET') {
            return $_GET;
        }

        //Si existe la cabecera /form-xx devolver datos pasados por formulario(POST)
        if (in_array($this->getHeader('content-type'), $this->content_type)) {
            return $_POST;
        }

        //Obtener datos de flujos php://input
        $data = file_get_contents('php://input');

        //return $data;
        if ($this->getHeader('content-type') == 'application/json') { #Testear esta parte luego con un JSON
            return json_decode($data, true);
        } else {
            return explode('=', $data);
        }
    }

    /**
     * Obtener solicitud de destino
     */
    public function getTarget(): string
    {
        return isset($this->target) ? $this->target : '/';
    }

    /**
     * Establecer solicitud de destino
     * @see http://tools.ietf.org/html/rfc7230#section-5.3 (para los diversos
     * formularios de destino de solicitud permitidos en mensajes de solicitud)
     */
    public function setTarget(string $target = null): static
    {
        $this->target = $target;
        return $this;
    }

    /**
     * Obtener metodo http
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Establecer metodo http
     */
    public function setMethod(string $method): static
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * Verificar metodo http
     */
    public function hasMethod(string $method): bool
    {
        return (strcasecmp($this->method, $method) == 0);
    }

    /**
     * Obtener Uri
     */
    public function getUri(): Uri
    {
        return $this->uri;
    }

    /**
     * Establecer Uri
     */
    public function setUri(Uri $uri, bool $preserv_host = false): static
    {
        $this->uri = $uri;

        if (!$preserv_host || !$this->hasHeader('host') || $this->getHeader('host') != '') {
            $this->setHeader('host', $uri->getHost());
        }

        return $this;
    }
}
