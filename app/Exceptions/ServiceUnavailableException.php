<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ServiceUnavailableException extends BaseException
{
    protected $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
}
