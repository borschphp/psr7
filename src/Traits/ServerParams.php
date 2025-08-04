<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

trait ServerParams
{

    /** @var array<string, string> $server_params */
    private array $server_params = [];

    /**
     * @inheritDoc
     *
     * @return array<string, string>
     */
    public function getServerParams(): array
    {
        return $this->server_params;
    }
}
