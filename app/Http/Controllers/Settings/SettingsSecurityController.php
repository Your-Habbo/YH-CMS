<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\TrustedDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use App\Http\Traits\PjaxTrait;

class SettingsSecurityController extends Controller
{
    use PjaxTrait;
    public function index()
    {
        $user = Auth::user();
    
        // Assuming sessions are stored in the 'sessions' table
        $sessions = DB::table('sessions')->where('user_id', $user->id)->get();
        $trustedDevices = TrustedDevice::where('user_id', $user->id)->get();
    
        // Add device and platform information to each session
        foreach ($sessions as $session) {
            $agent = new \Jenssegers\Agent\Agent(); // Make sure to import the Agent class
            $agent->setUserAgent($session->user_agent); // Assuming 'user_agent' is stored in sessions table
    
            $session->device = $agent->device();
            $session->platform = $agent->platform();
            $session->browser = $agent->browser(); 
    
            // Handling last_activity and created_at
            $session->started_at = $session->created_at ?? $session->last_activity; // Default to created_at or last_activity
            $session->last_active_at = $session->last_activity ?? now(); // Ensure there's a fallback if last_activity isn't present
        }
    
        Return $this->view('settings.security', compact('user', 'sessions', 'trustedDevices'));
    }
    



    /**
     * Logout from a specific session.
     *
     * @param  int  $sessionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutSession($sessionId)
    {
        $session = Session::findOrFail($sessionId);
        $session->delete();

        return redirect()->route('settings.security')->with('success', 'Session logged out successfully.');
    }

    /**
     * Remove a trusted device.
     *
     * @param  int  $deviceId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revoke(TrustedDevice $device)
    {
        if ($device->user_id !== auth()->id()) {
            abort(403);
        }

        $device->delete();

        return back()->with('status', 'Trusted device has been revoked.');
    }

    /**
     * Toggle Two-Factor Authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle2FA(Request $request)
    {
        $user = Auth::user();
    
        if ($user->two_factor_secret) {
            $this->disable($request, new DisableTwoFactorAuthentication);
            return redirect()->route('security.index')->with('status', 'Two-Factor Authentication disabled successfully.');
        } else {
            return redirect()->route('two-factor.enable-token');
        }
    }
    /**
     * Update login alerts settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLoginAlerts(Request $request)
    {
        $user = Auth::user();
        $user->email_alerts = $request->has('email_alerts');
        $user->sms_alerts = $request->has('sms_alerts');
        $user->save();

        return redirect()->route('settings.security')->with('success', 'Login alerts updated successfully.');
    }



    public function trustDevice(Request $request)
    {
        $user = Auth::user();

        TrustedDevice::create([
            'user_id' => $user->id,
            'device_name' => $request->input('device_name'), // You might want to auto-detect this
            'device_ip' => $request->ip(),
            'device_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->route('settings.security')->with('success', 'Device trusted successfully!');
    }


    public function logoutAllSessions()
    {
        $user = Auth::user();

        // Delete all sessions for the user except the current one
        DB::table('sessions')->where('user_id', $user->id)->delete();

        return redirect()->route('settings.security')->with('success', 'All sessions logged out successfully.');
    }

}
