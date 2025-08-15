<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shuttlecock;
use App\Models\BdmField;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $bdm_fields = BdmField::all(); // Data lapangan
        $shuttles = Shuttlecock::all();
        // $shuttles = Shuttlecock::where('is_available', true)->get(); // Data shuttle tersedia

        return view('user.dashboard', compact('bdm_fields', 'shuttles'));
    }
}
