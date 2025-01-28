<?php

namespace App\Jobs\User;

use App\Constants\ImportConstants;
use App\Constants\StatusConstants;
use App\Jobs\Job;
use App\Models\Import;
use App\Models\User;

class FinishImportJob extends Job
{
    public function __construct(
        private int $existsUsersCount,
        private int $importId,
    ) {
    }

    public function handle(): void
    {
        $allUsersCount = User::query()->count();
        $newUsersCount = $allUsersCount - $this->existsUsersCount;

        Import::query()
            ->where('id', $this->importId)
            ->update([
                'all_counts' => $allUsersCount,
                'new_counts' => $newUsersCount,
                'exists_counts' => $this->existsUsersCount,
                'status' => StatusConstants::SUCCESS,
                'type' => ImportConstants::USER_TYPE,
            ]);
    }
}
