<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasTimestamps;

    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
