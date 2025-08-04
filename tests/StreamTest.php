<?php

namespace Borsch\Tests;

use Borsch\Http\Stream;
use Http\Psr7Test\StreamIntegrationTest;

class StreamTest extends StreamIntegrationTest
{

    public function createStream($data)
    {
        return new Stream($data);
    }
}
