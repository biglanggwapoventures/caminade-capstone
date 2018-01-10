@extends('admin.layouts.main')
@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Appointment</h2>
    </div>
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('create') }}">Create new appointment</a>
    </div>
</div>
<table class="table table-striped mt-3">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Date and time</th>
            <th>Pet(s) and Service(s)</th>
            <th>Total Payable</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($resourceList As $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->customer->fullname }}</td>
                <td>{{ date_create($row->appointment_date)->format('F d,Y') }} @ {{ date_create_immutable_from_format('H:i:s', $row->appointment_time)->format('h:i A') }}</td>
                <td>
                    <ul class="list-unstyled pl-0">
                        @foreach($row->line AS $line)
                            <li > {{ $line->pet->name }} &middot; {{ $line->service->name }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ number_format($row->getTotalAmount(), 2) }} php</td>
                <td>{{ $row->appointment_status }}</td>
                <td>
                    @include('components.form.index-actions', ['id' => $row->id])
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">Empty table</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
