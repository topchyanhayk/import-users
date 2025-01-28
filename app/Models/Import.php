<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $table = 'imports';

    protected $fillable = [
        'error_message',
        'status',
        'exists_counts',
        'new_counts',
        'all_counts',
        'type',
    ];
}
