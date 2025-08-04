<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use InvalidArgumentException;
use Psr\Http\Message\{ServerRequestInterface, UploadedFileInterface};

trait UploadedFiles
{

    /** @var UploadedFileInterface[] $uploaded_files */
    private array $uploaded_files = [];

    /**
     * @inheritDoc
     *
     * @return UploadedFileInterface[]
     */
    public function getUploadedFiles(): array
    {
        return $this->uploaded_files;
    }

    /**
     * @inheritDoc
     *
     * @param UploadedFileInterface[] $uploadedFiles
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        foreach ($uploadedFiles as $uploadedFile) {
            if (!($uploadedFile instanceof UploadedFileInterface)) {
                throw new InvalidArgumentException(
                    'Uploaded files must be an array of UploadedFileInterface instances.'
                );
            }
        }

        $new = clone $this;
        $new->uploaded_files = $uploadedFiles;

        return $new;
    }
}
