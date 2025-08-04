<?php

namespace Borsch\Tests;

use Borsch\Http\ServerRequest;
use Http\Psr7Test\ServerRequestIntegrationTest;
use Laminas\Diactoros\UriFactory;
use Psr\Http\Message\UriInterface;

class ServerRequestTest extends ServerRequestIntegrationTest
{

    public function createSubject()
    {
        return new ServerRequest('GET', $this->buildUri('http://www.example.com'), server_params: $_SERVER);
    }

    protected function buildUri($uri): UriInterface
    {
        return (new UriFactory())->createUri($uri);
    }
}
