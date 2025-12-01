<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    /** @use HasFactory<\Database\Factories\UrlFactory> */
    use HasFactory;

    protected $fillable = [
        'original_url',
        'short_url',
        'click_count',
    ];

    protected $casts = [
        'click_count' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'short_url';
    }
}
