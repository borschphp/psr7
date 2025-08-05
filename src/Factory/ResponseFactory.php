<?php declare(strict_types=1);

namespace Borsch\Http\Factory;

use Borsch\Http\Response;
use Psr\Http\Message\{ResponseFactoryInterface, ResponseInterface, StreamFactoryInterface};

readonly class ResponseFactory implements ResponseFactoryInterface
{

    public function __construct(
        private StreamFactoryInterface $stream_factory = new StreamFactory()
    ) {}

    /**
     * @inheritDoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response(
            $code,
            $this->stream_factory->createStream(''),
            reason_phrase: $reasonPhrase
        );
    }
}