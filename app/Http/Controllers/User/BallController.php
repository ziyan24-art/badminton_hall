<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ball;

class BallController extends Controller
{
    public function index()
    {
        $balls = Ball::all();
        return view('user.balls.index', compact('balls'));
    }
}
