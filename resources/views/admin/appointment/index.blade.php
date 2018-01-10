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
{!! Form::open(['url' => route('admin.appointment.index'), 'method' => 'GET', 'class' => 'form-inline mb-2 mt-2']) !!}

  <div class="form-group">
    <label for="inputPassword2" class="ml-1">Customer</label>
    {!! Form::select('customer_id', $customerList, null, ['class' => 'custom-select ml-1', '']) !!}
  </div>
  <div class="form-group">
    <label for="inputPassword2" class="ml-1">From</label>
    {!! Form::date('from', null, ['class' => 'form-control ml-1', '']) !!}
  </div>
  <div class="form-group">
    <label for="inputPassword2" class="ml-1">To</label>
    {!! Form::date('to', null, ['class' => 'form-control ml-1', '']) !!}
  </div>
  <div class="form-group">
    <label for="inputPassword2" class="ml-1">Status</label>
    {!! Form::select('status', ['' => '** ALL **', 'PENDING' => 'Pending', 'APPROVED' => 'Approved', 'DENIED' => 'Rejected'], null, ['class' => 'custom-select ml-1', '']) !!}
  </div>
  <button type="submit" class="btn btn-danger ml-2">Filter</button>
{!! Form::close() !!}
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Assigned<br>Doctor</th>
            <th>Schedule</th>
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
                <td>{{ $row->doctor->fullname }}</td>
                <td>{{ date_create($row->appointment_date)->format('F d, Y') }} @ {{ date_create_immutable_from_format('H:i:s', $row->appointment_time)->format('h:i A') }}</td>
                <td>
                    <ul class="list-unstyled pl-0">
                        @foreach($row->line AS $line)
                            <li > {{ $line->pet->name }} &middot; {{ $line->service->name }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ number_format($row->getTotalAmount(), 2) }} php</td>
                <td>
                    {{ $row->appointment_status }}
                    @if($row->is('denied'))
                        <p class="text-danger mb-0">{{ $row->status_remarks }}</p>
                    @endif
                </td>
                <td>
                    @include('components.form.index-actions', ['id' => $row->id, 'hideRemove' => true])
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
