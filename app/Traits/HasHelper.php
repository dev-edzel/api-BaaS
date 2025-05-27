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

    public function resourceParser($request, $resource, $data = [], $auth = true): array
    {
        $data = array_filter(
            array_merge(
                $data,
                $request->validated() ?: $request->all()
            ),
            fn($val) => !is_null($val)
        );

        $ini = $resource->getAttributes();

        $resource->update($data);

        return $this->parseChanges(
            $ini,
            $resource->getChanges(),
            $resource->getHidden()
        );
    }

    public function parseChanges($ini, $fin, $hidden = []): array
    {
        $changes = [];

        foreach ($fin as $key => $val) {
            if (in_array($key, $hidden)) {
                continue;
            }

            $changes[$key] = [
                'initial' => $ini[$key],
                'final' => $val
            ];
        }

        return $changes;
    }
}
