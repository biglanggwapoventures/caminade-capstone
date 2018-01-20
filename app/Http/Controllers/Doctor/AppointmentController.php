<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;

class AppointmentController extends AdminAppointmentController
{
    public function index()
    {
        return view('doctor.appointment.index');
    }

    public function beforeEdit($model)
    {
        // perform normal parent opertions
        parent::beforeEdit($model);
        // before the view loads, change the dir
        $this->viewBaseDir = 'admin.appointment';
    }
}
