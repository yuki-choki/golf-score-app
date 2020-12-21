<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $sort = $request->sort;
        
        return view('users.index', compact('user'));
    }
}
