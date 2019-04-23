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
                        <th class="bg-secondary text-white">ID</th>
                        <th class="bg-secondary text-white">Date</th>
                        <th class="bg-secondary text-white">Doctor</th>
                        <th class="bg-secondary text-white">Total Payable</th>
                        <th class="bg-secondary text-white">Status</th>
                        <th class="bg-secondary text-white">remarks</th>
                        <th class="bg-secondary text-white"></th>
                        <th class="bg-secondary text-white"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resourceList As $row)
                        <tr>
                            <td>#{{ $row->id }}</td>
                            <td>{{ date_create("{$row->appointment_date} {$row->appointment_time}")->format('m/d/Y \@ h:i A') }}</td>
                            <td>{{ $row->doctor->fullname }}</td>
                            <td>{{ number_format($row->getTotalAmount(), 2) }} php</td>

                            <td>
                                {{ $row->appointment_status }}
                            </td>
                            <td>
                                {{ $row->remarks }}
                            </td>
                            <td>
                                @if($row->is('pending'))
                                    @include('components.form.index-actions', ['id' => $row->id, 'hideRemove' => true])
                                @endif
                            </td>
                            <td>
                                @if($row->is('pending'))
                                    {!! Form::open(['url' => route('user.appointment.cancel', ['appointmentId' => $row->id])]) !!}
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="javascript:return confirm('Are you sure? This cannot be undone!')">Cancel</button>
                                    {!! Form::close() !!}
                                @else
                                    <a href="{{ MyHelper::resource('show', ['id' => $row->id]) }}" class="btn btn-warning btn-sm">View</a>
                                @endif
                            </td>
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
