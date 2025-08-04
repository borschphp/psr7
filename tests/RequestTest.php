<?php

namespace Borsch\Tests;

use Borsch\Http\Request;
use Http\Psr7Test\RequestIntegrationTest;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFileFactory;
use Laminas\Diactoros\UriFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

class RequestTest extends RequestIntegrationTest
{

    public function createSubject(): RequestInterface
    {
        return new Request('GET', $this->buildUri('http://www.example.com'));
    }

    protected function buildUri($uri): UriInterface
    {
        return (new UriFactory())->createUri($uri);
    }

    protected function buildStream($data): StreamInterface
    {
        return (new StreamFactory())->createStream($data);
    }

    protected function buildUploadableFile($data): UploadedFileInterface
    {
        return (new UploadedFileFactory())->createUploadedFile($this->buildStream($data), strlen($data));
    }
}
