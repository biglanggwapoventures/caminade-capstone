@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <div class="row mt-3">
        <div class="col-3">
            <a class="btn  btn-success btn-block" href="{{ route('user.appointment.create') }}"><i class="fas fa-plus"></i> New appointment</a>
        </div>
        <div class="col">
            <h4 class="mb-3">Appointments</h4>
            <table class="table ">
                <thead>
                    <tr>
                        <th class="bg-secondary text-white">Date and time</th>
                        <th class="bg-secondary text-white">Pet(s) and Service(s)</th>
                        <th class="bg-secondary text-white">Total Payable</th>
                        <th class="bg-secondary text-white">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resourceList As $row)
                        <tr>
                            <td>{{ date_create($row->appointment_date)->format('F d, Y') }} @ {{ date_create_immutable_from_format('H:i:s', $row->appointment_time)->format('h:i A') }}</td>
                            <td>
                                <ul class="list-unstyled pl-0">
                                    @foreach($row->line AS $line)
                                        <li > {{ $line->pet->name }} &middot; {{ $line->service->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ number_format($row->getTotalAmount(), 2) }} php</td>
                            <td>{{ $row->appointment_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Empty table</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('modals')

@endpush
