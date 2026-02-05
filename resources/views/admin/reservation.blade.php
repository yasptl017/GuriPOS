@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Reservations')}}</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{__('admin.Reservations')}}</h1>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin.SN')}}</th>
                                            <th>{{__('admin.Name')}}</th>
                                            <th>{{__('admin.Email')}}</th>
                                            <th>{{__('admin.Phone')}}</th>
                                            <th>{{__('admin.Date & Time')}}</th>
                                            <th>{{__('admin.Person')}}</th>
                                            <th>{{__('admin.Status')}}</th>
                                            <th>{{__('admin.Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reservations as $index => $reservation)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $reservation->name }}</td>
                                            <td>{{ $reservation->email }}</td>
                                            <td>{{ $reservation->phone }}</td>
                                            <td>
                                                {{ date('d M, Y', strtotime($reservation->reserve_date)) }}
                                                <br>
                                                {{ $reservation->reserve_time }}
                                            </td>
                                            <td>{{ $reservation->person_qty }}</td>
                                            <td>
                                                @if ($reservation->reserve_status == 1)
                                                <span class="badge badge-success">{{__('admin.Approved')}}</span>
                                                @elseif ($reservation->reserve_status == 3)
                                                <span class="badge badge-success">{{__('admin.Completed')}}</span>
                                                @elseif ($reservation->reserve_status == 4)
                                                <span class="badge badge-danger">{{__('admin.Declined')}}</span>
                                                @else
                                                <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $reservation->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#orderModalId-{{ $reservation->id }}" class="btn btn-warning btn-sm"><i class="fas fa-cog" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<!-- Modals -->
@foreach ($reservations as $reservation)
<div class="modal fade" id="orderModalId-{{ $reservation->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('admin.Reservation Status')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="{{ route('admin.update-reservation-status',$reservation->id) }}" method="POST">
                        @method('PUT')
                        @csrf

                        <div class="form-group">
                            <label for="">{{__('admin.Change status')}}</label>
                            <select name="reserve_status" id="" class="form-control">
                                <option {{ $reservation->reserve_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                <option {{ $reservation->reserve_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Approved')}}</option>
                                <option {{ $reservation->reserve_status == 3 ? 'selected' : '' }} value="3">{{__('admin.Completed')}}</option>
                                <option {{ $reservation->reserve_status == 4 ? 'selected' : '' }} value="4">{{__('admin.Declined')}}</option>
                            </select>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                <button type="submit" class="btn btn-primary">{{__('admin.Update Status')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    function deleteData(id) {
        $("#deleteForm").attr("action", '{{ url("admin/delete-reservation/") }}' + "/" + id)
    }
</script>
@endsection
