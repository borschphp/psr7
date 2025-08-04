<?php

namespace Borsch\Tests;

use Borsch\Http\Response;
use Http\Psr7Test\ResponseIntegrationTest;
use Laminas\Diactoros\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseTest extends ResponseIntegrationTest
{

    /**
     * @inheritDoc
     */
    public function createSubject(): ResponseInterface
    {
        return new Response();
    }

    protected function buildStream($data): StreamInterface
    {
        return (new StreamFactory())->createStream($data);
    }
}
