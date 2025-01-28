<?php

namespace App\Jobs\User;

use App\Exceptions\FailedUpdateOrInsertUsersException;
use App\Jobs\Job;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Throwable;

class UpdateOrInsertUsersJob extends Job
{
    public function __construct(private string $cacheId)
    {
    }

    /**
     * @throws FailedUpdateOrInsertUsersException
     */
    public function handle(): void
    {
        try {
            $data = Cache::get($this->cacheId);
            Cache::forget($this->cacheId);

            User::query()->upsert($data, ['first_name', 'last_name'], ['age', 'email']);
        } catch (Throwable $e) {
            throw new FailedUpdateOrInsertUsersException($e->getMessage());
        }
    }
}
