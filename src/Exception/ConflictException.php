<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ConflictException extends Exception implements HttpCompliantExceptionInterface
{

    public function getHttpCode(): int
    {
        return Response::HTTP_CONFLICT;
    }

    public function getHttpResponseBody(): string
    {
        return $this->getMessage();
    }
}
