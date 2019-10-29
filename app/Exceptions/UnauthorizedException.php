<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends BaseException
{
    protected $statusCode = Response::HTTP_UNAUTHORIZED;
}
