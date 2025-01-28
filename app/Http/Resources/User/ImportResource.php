<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ImportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type,
            'all_counts' => $this->resource->all_counts,
            'exists_counts' => $this->resource->exists_counts,
            'new_counts' => $this->resource->new_counts,
            'error_message' => $this->resource->error_message,
            'status' => $this->resource->status,
        ];
    }
}
