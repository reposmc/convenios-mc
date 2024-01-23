<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Socialite;
use Str;
use Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Http;

class LoginSvController extends Controller
{
    /**
     * Redirect to ServiceProvider.
     **/
    public function redirectToProvider()
    {
        return Socialite::driver('loginsv')->redirect();
    }

    /**
     * Get the user returned by the ServiceProvider and get saved at the DB.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request)
    {
        try {
            $user = Socialite::driver('loginsv')->stateless()->user();
        } catch (\Exception $e) {
            return $e;
        }

        // check if the user exists
        $existingUser = User::where('email', $user->email)->first();

        if (!$existingUser) {
            try {
                $newUser = new User();
                $newUser->name =  $user["name"];
                $newUser->email =  $user["email"];

                $newUser->email_verified_at =  now();
                $newUser->last_name = '';
                $newUser->save();

                // Assign colector role by default
                $roleUser = Role::findOrFail(1);
                $newUser->assignRole($roleUser);

                $this->login($request, $newUser);
            } catch (\Throwable $th) {
                User::where('email', $user['email'])->delete();
                throw $th;
                return $th;
            }

            return redirect()->to('/home');
        } else {
            $existingUser->name =  $user["name"];
            $existingUser->email =  $user["email"];

            $existingUser->save();

            $this->login($request, $existingUser);
        }

        return redirect()->to('/home');
    }

    public function login($request, $user)
    {
        if ($this->attemptLogin($user)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }
    }

    protected function authenticated(Request $request, $user)
    {
        //
    }

    protected function guard()
    {
        return Auth::guard();
    }

    protected function attemptLogin($user)
    {
        return $this->guard()->login($user);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    public function dependencies(Request $request)
    {
        $response = Http::get(getenv('URL_LOGIN') . "/api/dependencies?itemsPerPage=-1");

        return response()->json([
            'data' => is_array($response->json()) ? $response->json()['records'] : [],
        ]);
    }
}
