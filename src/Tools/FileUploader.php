<?php

namespace Exhum4n\Components\Tools;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploader
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $disc;

    /**
     * @param string $path
     * @param string $disc
     */
    public function __construct(string $path, string $disc = 'public')
    {
        $this->path = $path;
        $this->disc = $disc;
    }

    public function upload(UploadedFile $file): string
    {
        return $file->storeAs($this->path, $file->getClientOriginalName(), $this->disc);
    }

    /**
     * @param string $path
     */
    public function delete(string $path): void
    {
        Storage::disk($this->disc)->delete($path);
    }
}
