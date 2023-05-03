<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price'
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
            $model->slug = Str::slug($model->name);
        });

        static::created(fn ($model) => Log::info($model->name . ' Criado com sucesso em: ' . now()));

        static::updated(fn ($model) => Log::info($model->name . ' atualizado com sucesso em ' . now()));
    }
}
