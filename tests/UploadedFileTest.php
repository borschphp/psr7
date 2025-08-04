<?php

namespace Borsch\Tests;

use Borsch\Http\Stream;
use Borsch\Http\UploadedFile;
use Http\Psr7Test\UploadedFileIntegrationTest;

class UploadedFileTest extends UploadedFileIntegrationTest
{

    public function createSubject()
    {
        return new UploadedFile(new Stream(fopen('php://temp', 'r+')));
    }
}
