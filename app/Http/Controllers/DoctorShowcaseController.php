<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class DoctorShowcaseController extends Controller
{
    public function __invoke(Request $request)
    {
        $doctors = User::doctors()->get();
        return view('doctor-showcase', [
            'doctors' => $doctors,
        ]);
    }
}
