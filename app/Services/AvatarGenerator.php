<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;

class AvatarGenerator
{
    public static function generateAvatarUrl($avatarConfig)
    {
        $baseUrl = URL::to('/habbo-imaging/avatarimage');
        $params = [
            'figure' => $avatarConfig,
            'size' => 'l',
            'head_direction' => 4,
            'direction' => 4
        ];

        return $baseUrl . '?' . http_build_query($params);
    }
}