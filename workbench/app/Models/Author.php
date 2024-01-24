<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasTimestamps;

    protected $guarded = [];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
