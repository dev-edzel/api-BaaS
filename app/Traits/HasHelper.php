<?php

namespace App\Traits;

trait HasHelper
{
    public function uploadImage($file, $directory): ?string
    {
        if ($file) {
            $path = $file->store("public/$directory");

            return basename($path);
        }

        return null;
    }
}
