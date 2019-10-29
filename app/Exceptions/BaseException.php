<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BaseException extends Exception
{
    protected $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
