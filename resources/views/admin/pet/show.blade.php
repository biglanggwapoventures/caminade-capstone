@extends('admin.layouts.main')


@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Pets</h2>
    </div>
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('index') }}">Back to list</a>
    </div>
</div>
 <hr>
<div class="row">
    <div class="col-sm-5">
        <h4 >Basic Information</h4>
        <table class="table table-sm table-bordered" style="table-layout: fixed">
            <tbody>
                <tr>
                    <td class="bg-info text-white">Name:</td>
                    <td class="text-center">{{ $resourceData->name }}</td>
                </tr>
                <tr>
                    <td class="bg-info text-white">Breed:</td>
                    <td class="text-center">{{ "{$resourceData->breed->description} ({$resourceData->breed->category->description})" }}</td>
                </tr>
                <tr>
                    <td class="bg-info text-white">Gender:</td>
                    <td class="text-center">{{ $resourceData->gender }}</td>
                </tr>
                <tr>
                    <td class="bg-info text-white">Reproductive Alteration:</td>
                    <td class="text-center">{{ $resourceData->reproductiveAlteration->description }}</td>
                </tr>
                <tr>
                    <td class="bg-info text-white">Birthdate:</td>
                    <td class="text-center">{{ $resourceData->birthdate ? date_create($resourceData->birthdate)->format('F d, Y') : 'N/A ' }}</td>
                </tr>
                <tr>
                    <td class="bg-info text-white">Owner:</td>
                    <td class="text-center">{{ $resourceData->owner->fullname }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-sm-5">
        <h4 >Appearance</h4>
        <table class="table table-sm table-bordered" style="table-layout: fixed">
            <tbody>
                <tr>
                    <td class="bg-info text-white">Color:</td>
                    <td class="text-center">{{ $resourceData->color }}</td>
                </tr>
                <!-- <tr>
                    <td class="bg-info text-white">Weight (kg):</td>
                    <td class="text-center">{{ $resourceData->weight ? number_format($resourceData->weight) : '' }}</td>
                </tr> -->
                <tr>
                    <td class="bg-info text-white">Characteristics:</td>
                    <td class="text-center">{{ $resourceData->physical_characteristics }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-9">
        <h4>Medical Records</h4>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="bg-info"></th>
                    <th class="text-white bg-info">Date</th>
                    <th class="text-white bg-info">Findings</th>
                    <th class="text-white bg-info">Doctor</th>
                    <th class="text-white bg-info">Appointment Ref #</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resourceData->medicalHistory() AS $log)
                    <tr>
                        <td class="border-0 text-center">{{ $loop->iteration }}</td>
                        <td class="border-left-0">{{ date_create($log->created_at)->format('m/d/Y h:i A') }}</td>
                        <td>{{ $log->findings }}</td>
                        <td>{{ $log->appointment->doctor->fullname }}</td>
                        <td>Appointment # {{ $log->appointment_id }}</td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center">No data recorded</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-9">
        <h4>Pet Logs</h4>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="bg-info"></th>
                    <th class="bg-info text-white">Date</th>
                    <th class="bg-info text-white">Log</th>
                    <th class="text-white bg-info">Appointment Ref #</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resourceData->logs() AS $log)
                    <tr>
                        <td class="border-0 text-center">{{ $loop->iteration }}</td>
                        <td>{{ $log->timestamp->format('m/d/Y h:i A') }}</td>
                        <td>{{ $log->remarks }}</td>
                        <td>Appointment # {{ $log->appointment_id }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No data recorded</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
