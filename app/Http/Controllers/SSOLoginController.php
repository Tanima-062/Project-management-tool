<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SSOLoginController extends Controller
{
    public function index(){
        return view('backend.auth.sso_login');
    }
}
