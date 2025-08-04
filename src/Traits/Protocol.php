<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Psr\Http\Message\MessageInterface;

trait Protocol
{

    private string $protocol_version = '1.1';

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->protocol_version;
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        if ($this->protocol_version === $version) {
            return $this;
        }

        $new = clone $this;
        $new->protocol_version = $version;

        return $new;
    }
}
