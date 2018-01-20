<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Pet;
use Auth;

class APIController extends Controller
{
    public function getPetsFromCustomer($customerId)
    {
        return response()->json([
            'result' => true,
            'data' => Pet::with('breed')->ownedBy($customerId)->get(),
        ]);
    }

    public function getDoctorAppointments()
    {
        $data = Appointment::approved()
            ->ofDoctor(Auth::id())
            ->with([
                'customer' => function ($q) {
                    $q->select('id', 'firstname', 'lastname');
                },
                'line.service',
                'line.pet.breed',
            ])
            ->get(['id', 'appointment_date', 'appointment_time', 'customer_id']);

        $data->each->calculateApproximateFinishTime();

        return response()->json([
            'result' => true,
            'data' => $data,
        ]);
    }
}
