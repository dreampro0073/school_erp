<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {
    public function login(){   
        return view('login');
    }

    public function captcha(Request $request): Response
    {
        $code = strtoupper(Str::random(6));
        $request->session()->put('login_captcha', strtolower($code));

        $svg = $this->buildCaptchaSvg($code);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    public function postLogin(Request $request){
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'captcha'  => ['required', 'string'],
        ]);

        $captchaInput = strtolower(trim((string) $request->input('captcha')));
        $captchaStored = strtolower((string) $request->session()->get('login_captcha', ''));
        $request->session()->forget('login_captcha');

        if ($captchaStored === '' || $captchaInput !== $captchaStored) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['captcha' => 'Invalid captcha. Please try again.']);
        }

        unset($credentials['captcha']);

        $user = User::where("email", $request->input("email"))->first();
        if($user){
            if(isset($user->end_date) && date("Y-m-d", strtotime($user->end_date)) < date("Y-m-d")){
                return Redirect::back()->with('failure','We’re sorry, but your account access has expired. Please contact management team to renew or extend your subscription or for further assistance.');
            };
        }

        $credentials["active"] = 0;

        if (Auth::attempt($credentials)) {

            DB::table('user_activities')->insert([
                "user_id"=>Auth::id(),
                "activity"=>"Login",
                "remark"=>"Login",
                "updated_at" => date("Y-m-d H:i:s"),
                "created_at" => date("Y-m-d H:i:s"),
            ]);

            $request->session()->regenerate();
            $request->session()->forget('login_captcha');

            $user = Auth::user();

            if ($user->priv == 1) {
                return redirect()->to('/super-admin/dashboard');
            }

            if ($user->priv == 2) {
                return redirect()->to('/admin/dashboard');
            }

            if ($user->priv == 3) {
                return redirect()->to('/teachers/dashboard');
            }

            if ($user->priv == 5) {
                return redirect()->to('/gurdian/dashboard');
            }

            return redirect()->to('students');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('failure', 'Invalid username or password');
    }

    private function buildCaptchaSvg(string $code): string
    {
        $bg = sprintf('#%02x%02x%02x', random_int(220, 245), random_int(220, 245), random_int(220, 245));
        $text = sprintf('#%02x%02x%02x', random_int(40, 90), random_int(40, 90), random_int(40, 90));

        $lines = '';
        for ($i = 0; $i < 5; $i++) {
            $lineColor = sprintf('#%02x%02x%02x', random_int(120, 190), random_int(120, 190), random_int(120, 190));
            $x1 = random_int(0, 150);
            $y1 = random_int(0, 50);
            $x2 = random_int(0, 150);
            $y2 = random_int(0, 50);
            $lines .= "<line x1=\"{$x1}\" y1=\"{$y1}\" x2=\"{$x2}\" y2=\"{$y2}\" stroke=\"{$lineColor}\" stroke-width=\"1\" />";
        }

        $letters = '';
        foreach (str_split($code) as $index => $char) {
            $x = 15 + ($index * 22) + random_int(-2, 2);
            $y = 33 + random_int(-4, 4);
            $rotate = random_int(-18, 18);
            $letters .= "<text x=\"{$x}\" y=\"{$y}\" fill=\"{$text}\" font-size=\"26\" font-family=\"monospace\" transform=\"rotate({$rotate} {$x} {$y})\">{$char}</text>";
        }

        return "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"150\" height=\"50\" viewBox=\"0 0 150 50\">"
            . "<rect width=\"150\" height=\"50\" rx=\"6\" ry=\"6\" fill=\"{$bg}\" />"
            . $lines
            . $letters
            . '</svg>';
    }

    public function logout(Request $request)
    {   
        if(Auth::user()){
            DB::table('user_activities')->insert([
                "user_id"=>Auth::id(),
                "activity"=>"Logout",
                "remark"=>"Logout",
                "updated_at" => date("Y-m-d H:i:s"),
                "created_at" => date("Y-m-d H:i:s"),
            ]);

            Auth::logout();

        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->to('/');
    }
}
