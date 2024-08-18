<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Traits\PjaxTrait;
use App\Models\User;
use App\Models\Admin\Image;
use App\Models\UserHabboLink;
use Carbon\Carbon;
use App\Jobs\CheckHabboMOT;

class ProfileController extends Controller
{   

    use PjaxTrait;

    /**
     * Show the profile settings page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();
        $banners = Image::where('for_banner', 1)->get();
        return $this->view('settings.profile', compact('user', 'banners'));
    }


    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'birthdate' => 'required|date',
        ]);

        $user->update($request->only('name', 'email', 'username', 'birthdate'));

        return redirect()->route('settings.profile')->with('success', 'Profile updated successfully!');
    }

    public function linkHabbo(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
        ]);
    
        $username = $request->input('username');
        $user = Auth::user();
    
        // Check if this username is already linked to another account
        if (UserHabboLink::where('habbo_origin_name', $username)->exists()) {
            return response()->json(['message' => 'This Habbo username is already linked to another account.'], 400);
        }
    
        // Call the Habbo API to verify the username
        $response = Http::get("https://origins.habbo.com/api/public/users", ['name' => $username]);
    
        if ($response->successful()) {
            $data = $response->json();
    
            // Generate the verification code
            $verificationCode = 'verify-' . bin2hex(random_bytes(4));
    
            // Create or update the Habbo link record
            $habboLink = UserHabboLink::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'habbo_origin_name' => $data['name'],
                    'habbo_unique_id' => $data['uniqueId'],
                    'habbo_figure_string' => $data['figureString'],
                    'habbo_member_since' => $data['memberSince'],
                    'habbo_origin_status' => 'Pending',
                    'habbo_verification_code' => $verificationCode,
                ]
            );
    
            // Dispatch a job to check the MOT status periodically
            dispatch(new CheckHabboMOT($habboLink))->delay(now()->addMinutes(1));
    
            return response()->json([
                'message' => 'Verification in progress. Please set your MOT in the game.',
                'verification_code' => $verificationCode
            ]);
        } else {
            return response()->json(['message' => 'Unable to verify the username. Please try again.'], 400);
        }
    }
    

    public function checkHabboStatus(UserHabboLink $habboLink)
    {
        // Re-check the user's MOT from the Habbo API
        $response = Http::get("https://origins.habbo.com/api/public/users", ['name' => $habboLink->habbo_origin_name]);

        if ($response->successful()) {
            $data = $response->json();

            if ($data['motto'] === $habboLink->habbo_verification_code) {
                // Verification successful
                $habboLink->habbo_origin_status = 'Verified';
                $habboLink->save();

                return true;
            }
        }

        return false;
    }

    public function getMotdCode()
    {
        $user = Auth::user();
        $habboLink = $user->habboLink;

        if ($habboLink && $habboLink->habbo_origin_status === 'Pending') {
            return response()->json(['motd_code' => $habboLink->habbo_verification_code]);
        }

        return response()->json(['message' => 'MOTD code not available.'], 400);
    }

    public function cancelHabboLink(Request $request)
    {
        $user = Auth::user();
        $habboLink = $user->habboLink;

        if ($habboLink && $habboLink->habbo_origin_status === 'Pending') {
            $habboLink->delete();
            return response()->json(['message' => 'Habbo Origin linking process canceled successfully.']);
        }

        return response()->json(['message' => 'No pending Habbo Origin linking process found.'], 400);
    }

    public function removeHabboLink(Request $request)
    {
        $user = Auth::user();
        $habboLink = $user->habboLink;
    
        if ($habboLink) {
            $habboLink->delete();
            return response()->json(['message' => 'Habbo Origin account link removed successfully.']);
        }
    
        return response()->json(['message' => 'No linked Habbo Origin account found.'], 400);
    }


    public function updateMot(Request $request)
    {
        $request->validate([
            'mot' => 'nullable|string|max:500',
        ]);
    
        $user = Auth::user();
        $user->mot = $request->input('mot');
        $user->save();
    
        return redirect()->route('settings.profile')->with('success', 'MOT updated successfully!');
    }
    
    public function updateForumSignature(Request $request)
    {
        $request->validate([
            'forum_signature' => 'nullable|string|max:500',
        ]);
    
        $user = Auth::user();
        $user->forum_signature = $request->input('forum_signature');
        $user->save();
    
        return redirect()->route('settings.profile')->with('success', 'Forum Signature updated successfully!');
    }




    public function updateProfileBanner(Request $request)
    {
        $user = Auth::user();
    
        $request->validate([
            'banner_image' => 'required|exists:images,filepath', // Validate the banner image path
        ]);
    
        // Update the user's profile banner
        $user->profile_banner = $request->input('banner_image');
        $user->save();
    
        return redirect()->route('settings.profile')->with('success', 'Profile banner updated successfully!');
    }
}
