<?php

namespace App\Console\Commands;

use App\Constants\ImportConstants;
use App\Constants\StatusConstants;
use App\Jobs\User\FinishImportJob;
use App\Jobs\User\UpdateOrInsertUsersJob;
use App\Models\Import;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

class ImportUsersCommand extends Command
{
    private ?int $importId;

    protected $signature = 'import:users {importId}';

    protected $description = 'Import users';

    public const DATA_URL = 'https://randomuser.me/api/?results=5000';

    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        try {
            $data = collect(json_decode(file_get_contents(self::DATA_URL), true))->first();

            $this->importId = $this->argument('importId');

            $insertedData = collect($data)->map(function ($item) use (&$fullNames) {
                $firstname = $item['name']['first'];
                $lastname = $item['name']['last'];

                return [
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'full_name' => $firstname . ' ' . $lastname,
                    'email' => $item['email'],
                    'age' => $item['dob']['age'],
                ];
            });

            $fullNames = $insertedData->pluck('full_name')->toArray();

            $existsUsersCount = User::query()->whereIn('full_name', $fullNames)->count();

            $chunks = $insertedData->chunk(500);

            $jobs = [];
            foreach ($chunks as $chunk) {
                $cacheId = Str::uuid()->toString();
                Cache::set($cacheId, $chunk->toArray());

                $jobs[] = new UpdateOrInsertUsersJob($cacheId);
            }

            $importId = $this->importId;

            Bus::batch($jobs)
                ->then(function (Batch $batch) use ($existsUsersCount, $importId) {
                   FinishImportJob::dispatch($existsUsersCount, $importId);
                })
                ->catch(function (Batch $batch, Throwable $e) {
                   throw $e;
                })
                ->dispatch();
        } catch (Throwable $e) {
            $this->changeImportStatusToError($e->getMessage());
            throw $e;
        }
    }

    private function changeImportStatusToError(string $errorMessage): void
    {
        Import::query()
            ->where('id', $this->importId)
            ->update([
                'status' => StatusConstants::ERROR,
                'type' => ImportConstants::USER_TYPE,
                'error_message' => $errorMessage,
            ]);
    }
}
