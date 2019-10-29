<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ForbiddenException extends  BaseException
{
    protected $statusCode = Response::HTTP_FORBIDDEN;
}
