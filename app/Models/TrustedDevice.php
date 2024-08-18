<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

class TrustedDevice extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'device_name', 'device_ip', 'device_agent'];

    public static function createFromRequest($user, $request)
    {
        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));

        return self::create([
            'user_id' => $user->id,
            'device_name' => $agent->platform() . ' ' . $agent->browser(),
            'device_ip' => $request->ip(),
            'device_agent' => $agent->browser() . ' on ' . $agent->platform(),
        ]);
    }
}