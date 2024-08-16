<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    use HasFactory;

    // Add 'image' to the fillable array
    protected $fillable = ['name', 'slug', 'description', 'image'];

    public function threads()
    {
        return $this->hasMany(ForumThread::class, 'category_id');
    }
}
