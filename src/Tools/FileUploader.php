<?php

namespace Exhum4n\Components\Tools;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploader
{
    protected string $path;
    protected string $disc;

    public function __construct(string $path, string $disc = 'public')
    {
        $this->path = $path;
        $this->disc = $disc;
    }

    public function upload(UploadedFile $file, ?string $fileName = null): string
    {
        if (is_null($fileName)) {
            $fileName = $file->getClientOriginalName();
        }

        return $file->storeAs($this->path, $fileName, $this->disc);
    }

    public function delete(string $path): void
    {
        Storage::disk($this->disc)->delete($path);
    }
}
