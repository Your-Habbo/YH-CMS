<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function threads()
    {
        return $this->hasMany(ForumThread::class, 'category_id');
    }
}
