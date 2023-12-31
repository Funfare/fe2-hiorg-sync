<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $org = \Auth::user()->organization;
        $users = $org->users()->orderBy('name')->paginate(20);

        return view('organizations.users.index', compact('org', 'users'));
    }
}
