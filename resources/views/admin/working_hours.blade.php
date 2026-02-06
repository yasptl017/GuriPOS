@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Working Hours')}}</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{__('admin.Working Hours')}}</h1>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.update-working-hours') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="20%">{{__('admin.Day')}}</th>
                                                <th width="25%">{{__('admin.Start Time')}}</th>
                                                <th width="25%">{{__('admin.End Time')}}</th>
                                                <th width="30%">{{__('admin.Full Day Off')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($workingHours as $workingHour)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="days[{{ $workingHour->id }}]" value="{{ $workingHour->day }}">
                                                    <strong>{{ $workingHour->day }}</strong>
                                                </td>
                                                <td>
                                                    <input
                                                        type="time"
                                                        name="start_times[{{ $workingHour->id }}]"
                                                        class="form-control start-time-{{ $workingHour->id }}"
                                                        value="{{ $workingHour->start_time }}"
                                                        {{ $workingHour->is_closed ? 'disabled' : '' }}
                                                    >
                                                </td>
                                                <td>
                                                    <input
                                                        type="time"
                                                        name="end_times[{{ $workingHour->id }}]"
                                                        class="form-control end-time-{{ $workingHour->id }}"
                                                        value="{{ $workingHour->end_time }}"
                                                        {{ $workingHour->is_closed ? 'disabled' : '' }}
                                                    >
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input closed-checkbox"
                                                            type="checkbox"
                                                            name="is_closed[{{ $workingHour->id }}]"
                                                            id="is_closed_{{ $workingHour->id }}"
                                                            value="1"
                                                            data-id="{{ $workingHour->id }}"
                                                            {{ $workingHour->is_closed ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label" for="is_closed_{{ $workingHour->id }}">
                                                            {{ $workingHour->is_closed ? __('admin.Closed') : __('admin.Open') }}
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            // Handle checkbox toggle for enabling/disabling time inputs
            $('.closed-checkbox').on('change', function() {
                var id = $(this).data('id');
                var isChecked = $(this).is(':checked');
                var label = $(this).next('label');

                if (isChecked) {
                    $('.start-time-' + id).prop('disabled', true);
                    $('.end-time-' + id).prop('disabled', true);
                    label.text('{{__('admin.Closed')}}');
                } else {
                    $('.start-time-' + id).prop('disabled', false);
                    $('.end-time-' + id).prop('disabled', false);
                    label.text('{{__('admin.Open')}}');
                }
            });
        });
    })(jQuery);
</script>

@endsection
