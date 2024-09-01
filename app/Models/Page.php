<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'layout',
        'content',
        'custom_css',
        'custom_js',
    ];
}
