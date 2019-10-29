<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ConflictException extends BaseException
{
    protected $statusCode = Response::HTTP_CONFLICT;
}
