<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

trait Port
{

    private ?int $port = null;

    /**
     * @inheritDoc
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function withPort(?int $port): UriInterface
    {
        if ($this->port === $port) {
            return $this;
        }

        if ($port !== null && ($port < 1 || $port > 65535)) {
            throw new InvalidArgumentException('Port must be between 1 and 65535.');
        }

        $new = clone $this;
        $new->port = $port;

        return $new;
    }
}
