<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentSMSController extends Controller
{
    public function __invoke(Request $request)
    {
        $input = $request->validate([
            'id' => [
                'required',
                Rule::exists('appointments')->where(function ($q) {
                    $q->where('appointment_status', '!=', 'PENDING');
                }),
            ],
            'action' => ['required', Rule::in(['update', 'reject', 'approve'])],
        ]);

        $appointment = Appointment::find($request->id);

        $result = false;
        switch ($input['action']) {
            case 'update':
                $result = $appointment->sendUpdateSMS();
                break;

            case 'reject':
                $result = $appointment->sendRejectionSMS();
                break;

            case 'approve':
                $result = $appointment->sendApprovalSMS();
                break;
        }

        if ($result === true) {
            session()->flash('SMS', ['result' => 'success', 'message' => 'SMS has been sent succesfully!']);
            return response()->json(['result' => true]);
        } else {
            session()->flash('SMS', ['result' => 'danger', 'message' => $result]);
            return response()->json(['result' => false, 'errors' => ['action' => [$result]]], 422);
        }
    }
}
