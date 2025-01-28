<?php

namespace App\Services\User\Actions;

use App\Http\Resources\User\ImportResource;
use App\Models\Import;

class GetLastImportInfoAction
{
    public function run(): ImportResource
    {
        $import = Import::query()
            ->orderByDesc('created_at')
            ->first();

        return new ImportResource($import);
    }
}
