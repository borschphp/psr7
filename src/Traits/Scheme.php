<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

trait Scheme
{

    private string $scheme;

    /**
     * @inheritDoc
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function withScheme(string $scheme): UriInterface
    {
        if ($this->scheme === $scheme) {
            return $this;
        }

        if (is_numeric($scheme) || strlen($scheme) <= 1) {
            throw new InvalidArgumentException('Scheme must be a valid non-empty string.');
        }

        $new = clone $this;
        $new->scheme = $scheme;

        return $new;
    }
}
