<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'code',
    ];

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class, 'category_id');
    }
}