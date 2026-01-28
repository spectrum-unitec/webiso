<?php

namespace App\Controllers\Fe;

use App\Controllers\BaseController;

class Login extends BaseController
{
    public function index()
    {
        return view('Fe/login/login', [
            'title' => 'Home Page'
        ]);
    }
}
