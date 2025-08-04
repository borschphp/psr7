<?php declare(strict_types=1);

namespace Borsch\Http\Factory;

use Borsch\Http\Stream;
use Psr\Http\Message\{StreamFactoryInterface, StreamInterface};
use RuntimeException;
use function fopen, fwrite, rewind;

class StreamFactory implements StreamFactoryInterface
{

    /**
     * @inheritDoc
     */
    public function createStream(string $content = ''): StreamInterface
    {
        $resource = fopen('php://temp', 'r+');
        if ($resource === false) {
            throw new RuntimeException('Unable to create a temporary stream resource.');
        }

        if ($content !== '') {
            fwrite($resource, $content);
            rewind($resource);
        }

        return new Stream($resource);
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $resource = fopen($filename, $mode);
        if ($resource === false) {
            throw new RuntimeException('Unable to create a temporary stream resource.');
        }

        return new Stream($resource);
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        if (!is_resource($resource)) {
            throw new RuntimeException('Provided resource is not a valid resource.');
        }

        return new Stream($resource);
    }
}
