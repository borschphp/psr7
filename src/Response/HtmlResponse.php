<?php declare(strict_types=1);

namespace Borsch\Http\Response;

use Borsch\Http\Response;

class HtmlResponse extends Response
{

    public function __construct(string $body, int $status_code = 200, array $headers = [])
    {
        parent::__construct($status_code, headers: $headers);

        $this->getBody()->write($body);
        $this->headers->set('Content-Type', ['text/html; charset=UTF-8']);
    }
}
