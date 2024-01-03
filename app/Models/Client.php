<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'phone_numbers' => 'array',
        'emails' => 'array',
    ];

    // Accessor to return the first phone number
    public function getFirstPhoneNumberAttribute()
    {
        return $this->phone_numbers[0]['number'] . ' - ' . ucwords($this->phone_numbers[0]['type']) ?? null;
    }

    //Assessor to return the first email
    public function getFirstEmailAttribute()
    {
        return $this->emails[0] ?? null;
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
