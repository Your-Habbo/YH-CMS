<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AvatarGenerator;
use Illuminate\Support\Facades\Auth;

class AvatarController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $avatarConfig = $user->avatar_config ?? 'lg-3058-82.hd-3092-2.ch-3022-64-64.cc-3002-1.hr-3256-61-40';
        $avatarUrl = $this->getAvatarUrl($avatarConfig);
    
        return view('user.avatar', compact('avatarConfig', 'avatarUrl'));
    }
    private function getAvatarUrl($figure)
{
    $baseUrl = url('/habbo-imaging/avatarimage');
    $params = [
        'figure' => $figure,
        'size' => 'l',
        'head_direction' => 4,
        'direction' => 4
    ];

    return $baseUrl . '?' . http_build_query($params);
}

    public function store(Request $request)
    {
        $user = Auth::user();
        $avatarCode = $request->input('habbo-avatar');
    
        // Extract the figure string and gender
        $parts = explode('&', $avatarCode);
        $figureString = $parts[0];
        $gender = explode('=', $parts[1])[1];
    
        // Combine figure string and gender into the format we want to save
        $avatarConfig = $figureString . '&gender=' . $gender;
    
        // Save the avatar configuration to the user's profile
        if ($user->updateAvatarConfig($avatarConfig)) {
            // Generate the avatar URL
            $avatarUrl = $user->getAvatarUrl();
    
            return redirect()->route('user.avatar')->with('success', 'Avatar updated successfully')->with('avatarUrl', $avatarUrl);
        } else {
            return redirect()->route('user.avatar')->with('error', 'Failed to update avatar');
        }
    }
}
