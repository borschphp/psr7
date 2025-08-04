<?php

namespace Borsch\Http\Response;

use Borsch\Http\Response;

class RedirectResponse extends Response
{

    public function __construct(string $location, int $status_code = 302, array $headers = [])
    {
        parent::__construct($status_code, headers: $headers);

        $this->headers->set('Location', [$location]);
    }
}
