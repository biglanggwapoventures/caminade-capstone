@extends('admin.layouts.main')
@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Boarding</h2>
    </div>
</div>
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Appointment #</th>
            <th>Customer</th>
            <th>Boarding start</th>
            <th>Boarding end</th>
            <th>Total Payable</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($resourceList As $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->appointment_id }}</td>
                <td>{{ data_get($row, 'appointment.customer.fullname') }}</td>
                <td>{{ date_create($row->timestamp_in)->format('F d, Y') }}</td>
                <td>{{ $row->timestamp_out ? date_create($row->timestamp_out)->format('F d, Y') : '' }}</td>
                <td class="text-right">{{ number_format($row->total_payable, 2) }}</td>
                <td>@include('components.form.index-actions', ['id' => $row->id, 'hideRemove' => true])</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">Empty table</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
