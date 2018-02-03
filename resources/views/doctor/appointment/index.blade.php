@extends('admin.layouts.main')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}">
@endpush

@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Appointment</h2>
    </div>
</div>
<div class="row h-100 mt-2">
    <div class="col-7" id="calendar"></div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/fullcalendar.min.js') }}"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        function showModal(data) {

            var modal = $('#appointment-details'),
                manageUrl = "{{ route('doctor.appointment.edit', ['id' => '__ID__']) }}";
            modal.find('.service-line').remove()
            modal.find('.modal-title').text('Appointment Ref # ' + data.id)
            modal.find('.customer').text(data.customer.fullname)
            modal.find('.date').text(moment(data.appointment_date, 'YYYY-MM-DD').format('MM/DD/YYYY'))
            modal.find('.start').text(moment(data.appointment_time, 'HH:mm').format('hh:mm a'))
            modal.find('.end').text(moment(data.approximate_finish_time, 'YYYY-MM-DD HH:mm').format('hh:mm a'));
            modal.find('.remarks').html(data['remarks'] || '<span class="text-danger"><em>Empty</em></span>');
            modal.find('.manage-link').attr('href', manageUrl.replace('__ID__', data.id));
            modal.modal('show');
        }

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'listMonth,month'
            },
            defaultView: 'listMonth',
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            navLinks: true,
            eventClick: function(calEvent, jsEvent, view) {

                showModal(calEvent.fullSrc);
                $(this).css('border-color', 'red');
            },
            events: function(start, end, timezone, callback) {
                $.ajax({
                    url: "{{ route('api:get-doctor-appointments') }}",
                    dataType: 'json',
                    data: {
                        // our hypothetical feed requires UNIX timestamps
                        start: start.unix(),
                        end: end.unix()
                    },
                    success: function(response) {
                        var events = [];
                        $.each(response['data'], function(i, v) {
                            console.log(v)
                            events.push({
                                title: '#' + v['id'] + ' ('+v['customer']['fullname']+')',
                                start: moment(v['appointment_date'] + ' ' + v['appointment_time'], 'YYYY-MM-DD HH:mm'),
                                end: moment(v['approximate_finish_time'], 'YYYY-MM-DD HH:mm'),
                                fullSrc: v
                            });
                        });
                        callback(events);
                    }
                });
            }
        });
    });
</script>
@endpush


@push('modals')
<div class="modal fade" id="appointment-details" tabindex="-1" role="dialog" aria-labelledby="register-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="register-title">Appointment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-bodyp p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="text-right border-0">Customer</td>
                            <td class="customer text-info border-0">Customer</td>
                            <td class="text-right border-0">Date</td>
                            <td class="date text-info border-0">Customer</td>
                        </tr>

                        <tr>
                            <td class="text-right border-0">Start Time</td>
                            <td class="start text-info border-0">Customer</td>
                            <td class="text-right border-0">Approximate End Time</td>
                            <td class="end text-info border-0">Customer</td>
                        </tr>
                        <tr>
                            <td class="text-right border-0 ">Remarks</td>
                            <td class="text-info border-0 remarks" colspan="3"></td>
                        </tr>
                        <tr class="d-none">
                            <td class="bg-info text-white">Pet</td>
                            <td class="bg-info text-white">Service</td>
                            <td class="bg-info text-white">Duration</td>
                            <td class="bg-info text-white">Price</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="" class="btn btn-success manage-link">Manage</a>
            </div>
        </div>
    </div>
</div>
@endpush
