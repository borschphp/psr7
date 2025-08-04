<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;

trait Method
{

    protected string $method;

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): RequestInterface
    {
        if ($this->method === $method) {
            return $this;
        }

        if (!strlen($method)) {
            throw new InvalidArgumentException('Method cannot be an empty string');
        }

        $new = clone $this;
        $new->method = $method;

        return $new;
    }
}
