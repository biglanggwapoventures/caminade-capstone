<?php

namespace App\Http\Controllers\Doctor;

use App\DoctorProfile;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Toast;

class UpdateDoctorProfileController extends Controller
{
    protected $model = null;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function showPage(Request $request)
    {
        return view('doctor.profile', [
            'profile' => Auth::user(),
            'doctorProfile' => Auth::user()->doctorProfile ?: new DoctorProfile,
        ]);
    }

    public function update(Request $request)
    {
        $input = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
            'gender' => ['required', Rule::in(['MALE', 'FEMALE'])],
            'email' => ['required', 'email', Rule::unique($this->model->getTable())->ignore(Auth::id())],
            'password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|same:password',
            'profile.photo' => 'image',
            'profile.specialization' => 'required',
            'profile.bio' => 'required',
            'profile.schedule' => 'array',
        ]);

        if (!trim($input['password'])) {
            unset($input['password']);
        }

        $doctor = Auth::user();
        $doctor->update($input);

        if ($request->hasFile('profile.photo_filepath')) {
            $input['profile']['photo_filepath'] = $request->file('profile.photo_filepath')->store("doctors/{$doctor->id}", 'public');
        }

        if ($doctor->doctorProfile()->exists()) {
            $input['profile']['schedule'] = json_encode($input['profile']['schedule']);
            $doctor->doctorProfile()->update($input['profile']);
        } else {
            $doctor->doctorProfile()->create($input['profile']);
        }

        Toast::success('Your profile has been successfully updated!');

        return response()->json([
            'result' => true,
        ]);
    }
}
