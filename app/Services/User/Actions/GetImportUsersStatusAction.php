<?php

namespace App\Services\User\Actions;

use App\Http\Resources\User\ImportResource;
use App\Models\Import;

class GetImportUsersStatusAction
{
    public function run(int $importId): ImportResource
    {
        $import = Import::query()->findOrFail($importId);

        return new ImportResource($import);
    }
}
