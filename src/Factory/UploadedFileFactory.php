<?php declare(strict_types=1);

namespace Borsch\Http\Factory;

use Borsch\Http\UploadedFile;
use Psr\Http\Message\{StreamInterface, UploadedFileFactoryInterface, UploadedFileInterface};
use const UPLOAD_ERR_OK;

class UploadedFileFactory implements UploadedFileFactoryInterface
{

    /**
     * @inheritDoc
     */
    public function createUploadedFile(StreamInterface $stream, ?int $size = null, int $error = UPLOAD_ERR_OK, ?string $clientFilename = null, ?string $clientMediaType = null): UploadedFileInterface
    {
        return new UploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }
}
