<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Client\Provider\GenericProvider;

class AuthController extends Controller
{


    public function redirect(GenericProvider $provider)
    {
        $url = $provider->getAuthorizationUrl(['scope' => 'openid']);

        return redirect($url);
    }


    public function login()
    {
        return view('login');
    }
    public function auth(GenericProvider $provider, Request $request)
    {
        try {

            // Try to get an access token using the authorization code grant.
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $request->get('code')
            ]);
            $hiorgUser = $provider->getResourceOwner($accessToken)->toArray();
            if(session('setAdminUser') === true) {
                $org = Organization::where('key', $hiorgUser['organisation_key'])->first();

                $org->hiorg_user = $hiorgUser['name'];
                $org->hiorg_token = $accessToken->jsonSerialize();
                $org->save();
                session()->put('setAdminUser', false);
                return redirect()->route('settings');
            }

            $org = Organization::updateOrCreate(['key' => $hiorgUser['organisation_key']], ['name' => $hiorgUser['organisation']]);
            $user = User::updateOrCreate(['key' => $hiorgUser['sub']], [
                'name' => $hiorgUser['name'],
                'email' => $hiorgUser['email'],
                'hiorg_token' => $accessToken->jsonSerialize(),
                'organization_id' => $org->id
            ]);
            if($org->wasRecentlyCreated) {
                $org->tabs()->createMany([
                    ['name' => 'Stammdaten'],
                    ['name' => 'OS Funktionen'],
                    ['name' => 'OS Gruppen'],
                    ['name' => 'Alarmgruppen'],
                    ['name' => 'Provisionierung'],
                ]);
                $user->update(['is_admin' => 1]);
            }
            Auth::login($user, true);
            return redirect()->route('home');
//        );

        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

            // Failed to get the access token or user details.
            return redirect()->route('home');
        }
    }

    public function logout()
    {
        $key = Auth::user()->organization->key;
        Auth::logout();
        return redirect('https://www.hiorg-server.de/?ov='.$key);
    }

    public function loginAsUser(User $user)
    {
        abort_unless(Auth::user()->is_admin && Auth::user()->organization_id === $user->organization_id, 403);
        \Session::put('impersonate_admin', Auth::user()->id);
        Auth::loginUsingId($user->id);
        return redirect()->route('home');
    }

    public function loginBack()
    {
        $user =  \Session::get('impersonate_admin');
        abort_if($user === null, 403);
        \Session::put('impersonate_admin', null);
        Auth::loginUsingId($user);
        return redirect()->route('home');
    }
}
