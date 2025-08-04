<?php

namespace Borsch\Tests;

use Borsch\Http\Uri;
use Http\Psr7Test\UriIntegrationTest;

class UriTest extends UriIntegrationTest
{

    public function createUri($uri)
    {
        return new Uri($uri);
    }
}
