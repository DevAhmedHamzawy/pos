<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpacePart extends Model
{
    protected $guarded = [];

    public function maintenance()
    {
        return $this->belongsToMany(Maintenance::class)->withPivot('quantity');
    }
}
