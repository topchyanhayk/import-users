<?php

namespace App\Services\User\Actions;

use App\Constants\ImportConstants;
use App\Constants\StatusConstants;
use App\Models\Import;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class ImportUsersActions
{
    /**
     * @throws Throwable
     */
    public function run(): int
    {
        try {
            $import = Import::query()->create([
                'type' => ImportConstants::USER_TYPE,
                'status' => StatusConstants::IN_PROGRESS,
            ]);

            Artisan::call('import:users', ['importId' => $import->id]);

            return $import->id;
        } catch (Throwable $e) {
            throw new $e;
        }
    }
}
