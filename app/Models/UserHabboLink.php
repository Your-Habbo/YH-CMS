<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHabboLink extends Model
{
    use HasFactory;

    protected $table = 'user_habbolink';

    protected $fillable = [
        'user_id',
        'habbo_origin_name',
        'habbo_unique_id',
        'habbo_figure_string',
        'habbo_member_since',
        'habbo_origin_status',
        'habbo_verification_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
