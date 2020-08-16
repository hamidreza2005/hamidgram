<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function deleteUser(Request $request , $userId)
    {
        $user = User::findOrFail($userId);
        $user->tokens()->delete();
        $user->delete();
        return response([],204);
    }
    
    
}
