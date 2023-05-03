<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $timestamp = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->timestamps = false;
            $model->created_at = now();
        });
    }

    protected $fillable = [
        'street',
        'zip_code',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
