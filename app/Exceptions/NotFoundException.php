<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends BaseException
{
    protected $statusCode = Response::HTTP_NOT_FOUND;
}
