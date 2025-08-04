<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

trait Body
{

    private StreamInterface $body;

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        if ($this->body === $body) {
            return $this;
        }

        $new = clone $this;
        $new->body = $body;

        return $new;
    }

}