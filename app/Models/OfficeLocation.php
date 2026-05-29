<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfficeLocation extends Model
{
    protected $fillable = ['name', 'latitude', 'longitude', 'radius', 'address'];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
