<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ExtraDataException extends BaseException
{
    /**
     * @var array
     */
    private $extraData = [];

    /**
     * @return array
     */
    public function getExtraData(): array
    {
        return $this->extraData;
    }

    /**
     * Adiciona dados ao array extraData
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
        return $this;
    }

    /**
     * @param int $status
     */
    public function setStatusCode($status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        if (in_array($status, [Response::HTTP_CONFLICT, Response::HTTP_UNPROCESSABLE_ENTITY])) {
            $this->statusCode = $status;
        }
    }
}
