<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Psr\Http\Message\ServerRequestInterface;

trait QueryParams
{

    /** @var array<string, string|int|float> $query_params */
    private array $query_params = [];

    /**
     * @inheritDoc
     *
     * @return array<string, string|int|float>
     */
    public function getQueryParams(): array
    {
        return $this->query_params;
    }

    /**
     * @inheritDoc
     *
     * @param array<string, string|int|float> $query
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        $new = clone $this;
        $new->query_params = $query;

        return $new;
    }
}
