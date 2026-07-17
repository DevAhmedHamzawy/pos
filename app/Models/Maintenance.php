<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $guarded = [];

    public function spaceParts()
    {
        return $this->belongsToMany(SpacePart::class)->withPivot('quantity', 'price');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
