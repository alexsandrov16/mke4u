<?php

namespace Mk4U\Http;

/**
 * Response class
 * 
 * 
 * 
 * Representación de una respuesta saliente del lado del servidor.
 * 
 * Según la especificación HTTP, esta interfaz incluye propiedades para cada uno de los siguientes:
 * 
 * - Versión del protocolo
 * - Código de estado y frase de motivo
 * - Encabezados
 * - Cuerpo del mensaje
 * 
 * Las respuestas se consideran inmutables; todos los métodos que puedan cambiar de estado DEBEN implementarse 
 * de manera que conserven el estado interno del mensaje actual y devuelvan una instancia que contenga 
 * el estado cambiado.
 */
class Response
{
    /** @param int código de estado HTTP*/
    protected int $code;

    /** @param string frase de motivo de respuesta asociada con el código de estado*/
    protected string $phrase;

    /** @param const estado de respuesta HTTP*/
    protected const STATUS = [
        //1xx: Informational - Request received, continuing process
        100 => 'Continue', //[RFC-ietf-httpbis-semantics, Section 15.2.1]
        101 => 'Switching Protocols', //[RFC-ietf-httpbis-semantics, Section 15.2.2]
        102 => 'Processing', //[RFC2518]
        103 => 'Early Hints', //[RFC8297]
        //104-199 Unassigned

        //2xx: Success - The action was successfully received, understood, and accepted
        200 => 'OK', //[RFC-ietf-httpbis-semantics, Section 15.3.1]
        201 => 'Created', //[RFC-ietf-httpbis-semantics, Section 15.3.2]
        202 => 'Accepted', //[RFC-ietf-httpbis-semantics, Section 15.3.3]
        203 => 'Non-Authoritative Information', //[RFC-ietf-httpbis-semantics, Section 15.3.4]
        204 => 'No Content', //[RFC-ietf-httpbis-semantics, Section 15.3.5]
        205 => 'Reset Content', //[RFC-ietf-httpbis-semantics, Section 15.3.6]
        206 => 'Partial Content', //[RFC-ietf-httpbis-semantics, Section 15.3.7]
        207 => 'Multi-Status', //[RFC4918]
        208 => 'Already Reported', //[RFC5842]
        //209-225 Unassigned
        226 => 'IM Used', //[RFC3229]
        //227-299 Unassigned

        //3xx: Redirection - Further action must be taken in order to complete the request
        300 => 'Multiple Choices', //[RFC-ietf-httpbis-semantics, Section 15.4.1]
        301 => 'Moved Permanently', //[RFC-ietf-httpbis-semantics, Section 15.4.2]
        302 => 'Found', //[RFC-ietf-httpbis-semantics, Section 15.4.3]
        303 => 'See Other', //[RFC-ietf-httpbis-semantics, Section 15.4.4]
        304 => 'Not Modified', //[RFC-ietf-httpbis-semantics, Section 15.4.5]
        305 => 'Use Proxy', //[RFC-ietf-httpbis-semantics, Section 15.4.6]
        //306 (Unused) [RFC-ietf-httpbis-semantics, Section 15.4.7]
        307 => 'Temporary Redirect', //[RFC-ietf-httpbis-semantics, Section 15.4.8]
        308 => 'Permanent Redirect', //[RFC-ietf-httpbis-semantics, Section 15.4.9]
        //309-399 Unassigned

        //4xx: Client Error - The request contains bad syntax or cannot be fulfilled
        400 => 'Bad Request', //[RFC-ietf-httpbis-semantics, Section 15.5.1]
        401 => 'Unauthorized', //[RFC-ietf-httpbis-semantics, Section 15.5.2]
        402 => 'Payment Required', //[RFC-ietf-httpbis-semantics, Section 15.5.3]
        403 => 'Forbidden', //[RFC-ietf-httpbis-semantics, Section 15.5.4]
        404 => 'Not Found', //[RFC-ietf-httpbis-semantics, Section 15.5.5]
        405 => 'Method Not Allowed', //[RFC-ietf-httpbis-semantics, Section 15.5.6]
        406 => 'Not Acceptable', //[RFC-ietf-httpbis-semantics, Section 15.5.7]
        407 => 'Proxy Authentication Required', //[RFC-ietf-httpbis-semantics, Section 15.5.8]
        408 => 'Request Timeout', //[RFC-ietf-httpbis-semantics, Section 15.5.9]
        409 => 'Conflict', //[RFC-ietf-httpbis-semantics, Section 15.5.10]
        410 => 'Gone', //[RFC-ietf-httpbis-semantics, Section 15.5.11]
        411 => 'Length Required', //[RFC-ietf-httpbis-semantics, Section 15.5.12]
        412 => 'Precondition Failed', //[RFC-ietf-httpbis-semantics, Section 15.5.13]
        413 => 'Content Too Large', //[RFC-ietf-httpbis-semantics, Section 15.5.14]
        414 => 'URI Too Long', //[RFC-ietf-httpbis-semantics, Section 15.5.15]
        415 => 'Unsupported Media Type', //[RFC-ietf-httpbis-semantics, Section 15.5.16]
        416 => 'Range Not Satisfiable', //[RFC-ietf-httpbis-semantics, Section 15.5.17]
        417 => 'Expectation Failed', //[RFC-ietf-httpbis-semantics, Section 15.5.18]
        //418 (Unused) [RFC-ietf-httpbis-semantics, Section 15.5.19]
        //419-420 Unassigned
        421 => 'Misdirected Request', //[RFC-ietf-httpbis-semantics, Section 15.5.20]
        422 => 'Unprocessable Content', //[RFC-ietf-httpbis-semantics, Section 15.5.21]
        423 => 'Locked', //[RFC4918]
        424 => 'Failed Dependency', //[RFC4918]
        425 => 'Too Early', //[RFC8470]
        426 => 'Upgrade Required', //[RFC-ietf-httpbis-semantics, Section 15.5.22]
        //427 Unassigned
        428 => 'Precondition Required', //[RFC6585]
        429 => 'Too Many Requests', //[RFC6585]
        //430 Unassigned
        431 => 'Request Header Fields Too Large', //[RFC6585]
        //432-450 Unassigned
        451 => 'Unavailable For Legal Reasons', //[RFC7725]
        //452-499 Unassigned

        //5xx: Server Error - The server failed to fulfill an apparently valid request
        500 => 'Internal Server Error', //[RFC-ietf-httpbis-semantics, Section 15.6.1]
        501 => 'Not Implemented', //[RFC-ietf-httpbis-semantics, Section 15.6.2]
        502 => 'Bad Gateway', //[RFC-ietf-httpbis-semantics, Section 15.6.3]
        503 => 'Service Unavailable', //[RFC-ietf-httpbis-semantics, Section 15.6.4]
        504 => 'Gateway Timeout', //[RFC-ietf-httpbis-semantics, Section 15.6.5]
        505 => 'HTTP Version Not Supported', //[RFC-ietf-httpbis-semantics, Section 15.6.6]
        506 => 'Variant Also Negotiates', //[RFC2295]
        507 => 'Insufficient Storage', //[RFC4918]
        508 => 'Loop Detected', //[RFC5842]
        //509 Unassigned
        510 => 'Not Extended (OBSOLETED)', //[RFC2774][status-change-http-experiments-to-historic]
        511 => 'Network Authentication Required', //[RFC6585]
        //512-599 Unassigned
    ];

    use Header;

    public function __construct(mixed $content = "", object|array $status = HttpStatus::Ok, array $headers = [], string $version = null)
    {
        if (!is_null($version)) {
            $this->setProtocolVersion($version);
        }

        if (is_array($status)) {
            //especifica el codigo de estado con la frase de motivo
            $this->setStatus($status[0], $status[1]);
        } else {
            //especifica el codigo de estado con la frase de motivo por defecto
            $this->setStatus($status->value);
        }

        //establecer cabeceras
        $this->setHeaders($headers);

        //establecer cuerpo del mensaje
        $this->setBody($content);
    }

    public function __toString()
    {
        return $this->send();
    }

    /**
     * Debuguear mensanje de la respuesta HTTP
     */
    public function __debugInfo(): array
    {
        return [
            "protocol" => "HTTP/{$this->getProtocolVersion()}",
            "code"     => $this->getStatusCode(),
            "phrase"   => $this->getReasonPhrase(),
            "headers"  => $this->getHeaders(),
            "body"     => $this->getBody()
        ];
    }

    /**
     * Obtiene el código de estado de respuesta.
     */
    public function getStatusCode(): int
    {
        return $this->code;
    }

    /**
     * Devuelve una instancia con el código de estado especificado y, opcionalmente, la frase de motivo.
     * 
     * Si no se especifica ninguna frase de motivo, las implementaciones PUEDEN optar por el valor predeterminado
     * a la frase de motivo recomendada por RFC 7231 o IANA para la respuesta
     * código de estado.
     * 
     * Este método DEBE implementarse de tal manera que conserve la
     * inmutabilidad del mensaje, y DEBE devolver una instancia que tenga la
     * Estado actualizado y frase de motivo.
     * 
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code El código de resultado entero de 3 dígitos que se establecerá.
     * @param string $reasonPhrase La frase de motivo a usar con el
     * código de estado proporcionado; si no se proporciona ninguno, las implementaciones PUEDEN
     * utilice los valores predeterminados como se sugiere en la especificación HTTP.
     * @return static
     * @throws \InvalidArgumentException Para argumentos de código de estado no válidos.
     */
    public function setStatus(int $code, string $reasonPhrase = '')
    {
        if ($code < 100 || $code > 599) {
            throw new \InvalidArgumentException("Argumentos de código de estado no válidos");
        }

        $this->code = $code;
        $this->phrase = empty($reasonPhrase) ? HttpStatus::code($code) : $reasonPhrase;

        return $this;
    }

    /**
     * Obtiene la frase del motivo de la respuesta asociada al código de estado.
     * 
     * Porque una frase de motivo no es un elemento obligatorio en una respuesta
     * línea de estado, el valor de la frase de motivo PUEDE estar vacío. Implementaciones MAYO
     * elija devolver la frase de motivo recomendada por RFC 7231 predeterminada (o aquellas
     * incluido en el Registro de códigos de estado HTTP de IANA) para la respuesta
     * código de estado.
     * 
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Frase de motivo; debe devolver una cadena vacía si no hay ninguna presente.
     */
    public function getReasonPhrase()
    {
        return $this->phrase;
    }

    /**
     * Envia el mensaje HTTP
     */
    protected function send(): string
    {
        header('HTTP/' . $this->getProtocolVersion() . ' ' . $this->getStatusCode() . ' ' . $this->getReasonPhrase());

        foreach ($this->getHeaders() as $name => $value) {
            header("$name: $value");
        }

        return $this->getBody();
    }

    /**
     * Devuelve cuerpo del mensaje como JSON
     */
    public static function json(array|object $content, object|array $status = HttpStatus::Ok, array $headers = []): static
    {
        $headers['content-type'] = 'application/json';
        return new static(json_encode($content, JSON_PRETTY_PRINT), $status, $headers);
    }

    /**
     * Devuelve cuerpo del mensaje como texto plano
     */
    public static function plain(string $content, object|array $status = HttpStatus::Ok, array $headers = []): static
    {
        $headers['content-type'] = 'text/plain';
        return new static($content, $status, $headers);
    }

    /**
     * Devuelve cuerpo del mensaje como HTML
     */
    public static function html(string $content, object|array $status = HttpStatus::Ok, array $headers = []): static
    {
        $headers['content-type'] = 'text/html';
        return new static($content, $status, $headers);
    }
}
