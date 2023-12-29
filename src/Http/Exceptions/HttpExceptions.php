<?php

namespace Mk4U\Http\Exceptions;

use Exception;
use Mk4U\Http\Response;
use RuntimeException;

/**
 * undocumented class
 */
class HttpExceptions extends Exception
{
    protected const file = '/src/page.php';

    public function __construct(private object $statusCode)
    {
        if (!is_object($statusCode)) {
            throw new RuntimeException("Error Processing HTTP Status Code");
        }
    }

    public function render(): Response
    {
        $data = [
            'code' => $this->statusCode->value,
            'message' => $this->statusCode->message()
        ];
        return Response::html(
            view(dirname(STORAGE, 1) . self::file, $data),
            $this->statusCode
        );
    }
}
