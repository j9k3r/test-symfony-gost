<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class NoContentException extends Exception implements HttpCompliantExceptionInterface
{

    public function getHttpCode(): int
    {
        return Response::HTTP_NO_CONTENT;
    }

    public function getHttpResponseBody(): string
    {
        return $this->getMessage();
    }
}
