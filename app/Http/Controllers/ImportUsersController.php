<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetImportUsersStatusRequest;
use App\Http\Requests\GetLastImportInfoRequest;
use App\Http\Requests\ImportUsersRequest;
use App\Http\Resources\User\ImportResource;
use App\Services\User\Actions\GetImportUsersStatusAction;
use App\Services\User\Actions\GetLastImportInfoAction;
use App\Services\User\Actions\ImportUsersActions;
use Illuminate\Http\JsonResponse;

class ImportUsersController extends Controller
{
    public function run(
        ImportUsersRequest $request,
        ImportUsersActions $importUsersActions,
    ) : JsonResponse {
        $importId = $importUsersActions->run();

        return response()->json(['importId' => $importId]);
    }

    public function getStatus(
        GetImportUsersStatusRequest $request,
        GetImportUsersStatusAction $getImportUsersStatusAction,
    ): ImportResource {
        return $getImportUsersStatusAction->run($request->getImportId());
    }

    public function getLastImport(
        GetLastImportInfoRequest $request,
        GetLastImportInfoAction $getLastImportInfoAction,
    ): ImportResource {
        return $getLastImportInfoAction->run();
    }


}
