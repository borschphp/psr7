<?php declare(strict_types=1);

namespace Borsch\Http;

use Psr\Http\Message\{StreamInterface, UploadedFileInterface};
use RuntimeException;
use const UPLOAD_ERR_OK, UPLOAD_ERR_EXTENSION;
use function dirname, fopen, is_writable;

class UploadedFile implements UploadedFileInterface
{

    private bool $moved = false;

    public function __construct(
        private readonly StreamInterface $stream,
        private readonly ?int $size = null,
        private readonly int $error = UPLOAD_ERR_OK,
        private readonly ?string $filename = null,
        private readonly ?string $media_type = null
    ) {
        if ($this->size !== null && $this->size < 0) {
            throw new RuntimeException('Size must be a non-negative integer or null.');
        }

        if ($this->error < UPLOAD_ERR_OK || $this->error > UPLOAD_ERR_EXTENSION) {
            throw new RuntimeException('Invalid error code provided.');
        }
    }

    /**
     * @inheritDoc
     */
    public function getStream(): StreamInterface
    {
        if ($this->moved) {
            throw new RuntimeException('Cannot retrieve stream after the file has been moved.');
        }

        return $this->stream;
    }

    /**
     * @inheritDoc
     */
    public function moveTo(string $targetPath): void
    {
        if ($this->moved) {
            throw new RuntimeException('File has already been moved.');
        }

        if ($targetPath === '') {
            throw new RuntimeException('Target path cannot be empty.');
        }

        if (!is_writable(dirname($targetPath))) {
            throw new RuntimeException('Target directory is not writable.');
        }

        $stream = $this->getStream();
        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        $resource = fopen($targetPath, 'w');
        if ($resource === false) {
            throw new RuntimeException('Failed to open target file for writing.');
        }

        $destination = new Stream($resource);

        while (!$stream->eof()) {
            if (!$destination->write($stream->read(8192))) {
                break;
            }
        }

        $this->moved = true;

        $this->stream->close();
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * @inheritDoc
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @inheritDoc
     */
    public function getClientFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @inheritDoc
     */
    public function getClientMediaType(): ?string
    {
        return $this->media_type;
    }
}
