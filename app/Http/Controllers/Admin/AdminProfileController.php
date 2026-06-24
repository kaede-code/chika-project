<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminProfileController extends Controller
{
    public function index()
    {
        return view('page.admin.profile');
    }
}
