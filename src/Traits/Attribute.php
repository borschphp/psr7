<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Psr\Http\Message\ServerRequestInterface;

trait Attribute
{

    /** @var ArrayCollection<string, mixed> */
    private ArrayCollection $attributes;

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes->get($name) ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $new = clone $this;
        $new->attributes = clone $this->attributes;

        $new->attributes->set($name, $value);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        if (!$this->attributes->containsKey($name)) {
            return $this;
        }

        $new = clone $this;
        $new->attributes = clone $this->attributes;

        $new->attributes->remove($name);

        return $new;
    }
}
