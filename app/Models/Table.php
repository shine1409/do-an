<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 'seats'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public static function createNewTable($validatedData)
    {
        return self::create([
            'number' => $validatedData['number'],
            'seats' => $validatedData['seats'],
        ]);
    }

    public function updateTable($validatedData)
    {
        return $this->update($validatedData);
    }
}
