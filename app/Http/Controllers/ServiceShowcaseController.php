<?php

namespace App\Http\Controllers;

use App\Service;

class ServiceShowcaseController extends Controller
{
    public function __invoke()
    {
        return view('service-showcase', [
            'data' => Service::whereServiceStatus('active')->orderBy('name')->get(),
        ]);
    }
}
