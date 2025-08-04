<?php declare(strict_types=1);

namespace Borsch\Http;

use Borsch\Http\Traits\{Protocol, Headers, Body};
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Http\Message\{MessageInterface, StreamInterface};
use RuntimeException;
use function fopen;

class Message implements MessageInterface
{

    use Protocol, Body, Headers;

    /**
     * @param string $protocol
     * @param ?StreamInterface $body
     * @param array<string, string[]> $headers
     */
    public function __construct(string $protocol, ?StreamInterface $body = null, array $headers = [])
    {
        $this->protocol_version = $protocol;
        $this->headers = new ArrayCollection($headers);

        if ($body === null) {
            $resource = fopen('php://temp', 'r+');
            if ($resource === false) {
                throw new RuntimeException('Failed to open temporary stream for body.');
            }

            $body = new Stream($resource);
        }

        $this->body = $body;
    }
}
