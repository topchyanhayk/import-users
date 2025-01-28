<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetImportUsersStatusRequest extends FormRequest
{
    public const IMPORT_ID = 'importId';

    public function rules(): array
    {
        return [];
    }

    public function getImportId(): int
    {
        return $this->route(self::IMPORT_ID);
    }
}
