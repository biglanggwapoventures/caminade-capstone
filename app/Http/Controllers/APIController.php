<?php

namespace App\Http\Controllers;

use App\Pet;

class APIController extends Controller
{
    public function getPetsFromCustomer($customerId)
    {
        return response()->json([
            'result' => true,
            'data' => Pet::with('breed')->ownedBy($customerId)->get(),
        ]);
    }
}
