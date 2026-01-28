<?php

namespace App\Controllers\Be;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        $title = 'Admin - Dashboard';
        return view('Be/home', compact('title'));
    }
}
