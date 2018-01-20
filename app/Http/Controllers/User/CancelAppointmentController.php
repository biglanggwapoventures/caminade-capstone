<?php

namespace App\Http\Controllers\User;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\Rules\AppointmentStatus;
use Illuminate\Http\Request;
use Validator;

class CancelAppointmentController extends Controller
{
    public function __invoke($appointmentId, Request $request)
    {
        $validator = Validator::make([
            'appointment_id' => $appointmentId,
        ], [
            'appointment_id' => new AppointmentStatus('pending'),
        ]);

        if ($validator->passes()) {
            Appointment::find($appointmentId)->markAsApproved();
        }

        return redirect()->back();
    }
}
