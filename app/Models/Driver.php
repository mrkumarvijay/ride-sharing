<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'latitude',
        'longitude',
        'is_available',
    ];

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }
}
