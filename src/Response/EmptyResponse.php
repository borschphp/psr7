<?php

namespace Borsch\Http\Response;

use Borsch\Http\Response;
use Borsch\Http\Stream;
use RuntimeException;

class EmptyResponse extends Response
{

    public function __construct(int $status_code = 204, array $headers = [])
    {
        $body = fopen('php://temp', 'r');
        if ($body === false) {
            throw new RuntimeException('Failed to open temporary stream for body.');
        }

        parent::__construct($status_code, new Stream($body),$headers);
    }
}
