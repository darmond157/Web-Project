<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Mohammadv184\ArCaptcha\ArCaptcha;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $admin = Admin::where('username', $request->username)->first();

        // if (Hash::check($request->password, $admin->password) && $this->verifyCaptcha($request->arcaptchaToken)) {
        if ($request->password == $admin->password && $this->verifyCaptcha($request->arcaptchaToken)) {
            $token = $admin->createToken('adminAuthToken')->plainTextToken;
            return response($token, 200);
        } else {
            return response('Authentication Error', 401);
        }
    }

    private function verifyCaptcha($token){
        $siteKey = '4nma7h0vqd';
        $secretKey = 'r8kveiqst3gaym9hdjpw';
        $arCaptcha = new ArCaptcha($siteKey, $secretKey);

        if ($arCaptcha->verify($token)) {
            return true;
        } else {
            return false;
        }
    }
}
