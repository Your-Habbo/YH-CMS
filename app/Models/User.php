<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Forum\ForumPost;


class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'avatar_config',
        'dob',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date',
    ];

    /**
     * Get the user's display name.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->name ?? $this->username;
    }

    public function confirmTwoFactorAuth($code)
    {
        $codeIsValid = app(TwoFactorAuthenticationProvider::class)
            ->verify(decrypt($this->two_factor_secret), $code);

        if ($codeIsValid) {
            $this->two_factor_confirmed = true;
            $this->save();

            return true;
        }

        return false;
    }

    /**
     * Get the user's avatar URL.
     *
     * @return string
     */
    public function getAvatarUrl()
    {
        $baseUrl = url('/habbo-imaging/avatarimage');
        $params = [
            'figure' => $this->avatar_config ?? 'hr-100-61-40.hd-180-2.ch-3077-64-64.lg-3058-82.sh-3115-1336.wa-2001-0&gender=M',
            'size' => 'l',
            'head_direction' => 2,
            'direction' => 2,
        ];
        return $baseUrl . '?' . http_build_query($params);
    }

    /**
     * Update the user's avatar configuration.
     *
     * @param string $avatarConfig
     * @return bool
     */
    public function updateAvatarConfig($avatarConfig)
    {
        $this->avatar_config = $avatarConfig;
        return $this->save();
    }

    public function incrementContributionPoints($points)
    {
        $this->increment('contribution_points', $points);
    }
    
    public function getHabboHeadUrl()
    {
        // Replace this with your actual logic to get the Habbo head URL
        return "https://www.habbo.com/habbo-imaging/avatarimage?figure={$this->avatar_config}&size=s&head_direction=3&gesture=sml";
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }
    public function forumPosts(): HasMany
    {
        return $this->hasMany(\App\Models\Forum\ForumPost::class);
    }

    public function getForumPostCountAttribute()
    {
        return $this->forumPosts()->count();
    }
}