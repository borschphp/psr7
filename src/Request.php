<?php

namespace Borsch\Http;

use Borsch\Http\Traits\{Method, RequestTarget, Uri};
use Psr\Http\Message\{RequestInterface, StreamInterface, UriInterface};

class Request extends Message implements RequestInterface
{

    use Method, Uri, RequestTarget;

    public function __construct(
        string $method,
        UriInterface $uri,
        ?StreamInterface $body = null,
        array $headers = [],
        string $protocol = '1.1'
    ) {
        parent::__construct($protocol, $body, $headers);

        $this->method = $method;
        $this->uri = $uri;
        $this->request_target = $this->uri->getPath() ?: '/';
    }
}
