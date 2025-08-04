<?php

namespace Borsch\Http\Response;

use Borsch\Http\Response;

class TextResponse extends Response
{

    public function __construct(string $body, int $status_code = 200, array $headers = [])
    {
        parent::__construct($status_code, headers: $headers);

        $this->getBody()->write($body);
        $this->headers->set('Content-Type', ['text/plain; charset=UTF-8']);
    }
}
