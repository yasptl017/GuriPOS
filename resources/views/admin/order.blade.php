@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $title }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
                    <div class="breadcrumb-item">{{ $title }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12"> <!-- Adjust the column width based on your layout -->
                    <form action="{{ route('admin.orders.export') }}" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="start_date">Start Date:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="end_date">End Date:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                            <div class="form-group col-md-3" style="display: none">
                                <label for="order_status">Order Status:</label>
                                <input type="number" class="form-control" value="{{$orderStatus}}" id="order_status" name="order_status">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="export_type">Export As:</label>
                                <select class="form-control" id="export_type" name="export_type">
                                    <option value="xlsx">XLSX</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3" style="padding-top: 30px;">
                                <button class="btn btn-primary" type="submit">Export</button>
                                <button class="btn btn-secondary" type="button" onclick="printTable()">Print</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice" id="printableTable">
                                    <table class="table table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{__('admin.SN')}}</th>
                                                <th width="10%">{{__('admin.Customer')}}</th>
                                                <th width="10%">{{__('admin.Phone')}}</th>
                                                <th width="5%">{{__('admin.Order Id')}}</th>
                                                <th width="10%">{{__('admin.Date')}}</th>
                                                <th width="7%">{{__('admin.Amount')}}</th>
                                                <!--<th width="10%">{{__('admin.Order Status')}}</th>-->
                                                <th width="7%">{{__('Order Type')}}</th>
                                                <th width="10%">{{__('admin.Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $index => $order)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ optional($order->orderAddress)->name }}</td>
                                                    <td>{{ optional($order->orderAddress)->phone }}</td>
                                                    <td>{{ $order->order_id }}</td>
                                                    <td>{{ $order->created_at->format('d F, Y') }}</td>
                                                    <td>{{ $setting->currency_icon }}{{ number_format((float)$order->grand_total, 2) }}</td>
                                                    <!--<td>
                                                        @if ($order->order_status == 1)
                                                            <span class="badge badge-success">{{__('admin.In Progress')}}</span>
                                                        @elseif ($order->order_status == 2)
                                                            <span class="badge badge-success">{{__('admin.Delivered')}}</span>
                                                        @elseif ($order->order_status == 3)
                                                            <span class="badge badge-success">{{__('admin.Completed')}}</span>
                                                        @elseif ($order->order_status == 4)
                                                            <span class="badge badge-danger">{{__('admin.Declined')}}</span>
                                                        @else
                                                            <span class="badge badge-warning">{{__('admin.Pending')}}</span>
                                                        @endif
                                                    </td>-->
                                                    <td><span class="badge badge-success">{{ $order->order_type }}</span></td>
                                                    <td>
                                                        <a href="{{ route('admin.order-show', $order->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                        <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $order->id }})">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                        @if($order->order_type != 'Dine-in')
                                                            <a href="javascript:;" data-toggle="modal" data-target="#orderModalId-{{ $order->id }}" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-truck" aria-hidden="true"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center">
    {{ $orders->links('pagination::bootstrap-4') }}
</div>

                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <!-- Modal -->
    @foreach ($orders as $order)
        <div class="modal fade" id="orderModalId-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('admin.Order Status')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="{{ route('admin.update-order-status', $order->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <label for="">{{__('admin.Payment')}}</label>
                                    <select name="payment_status" class="form-control">
                                        <option {{ $order->payment_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                        <option {{ $order->payment_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Success')}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('admin.Order')}}</label>
                                    <select name="order_status" class="form-control">
                                        <option {{ $order->order_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                        <option {{ $order->order_status == 1 ? 'selected' : '' }} value="1">{{__('admin.In Progress')}}</option>
                                        <option {{ $order->order_status == 2 ? 'selected' : '' }} value="2">{{__('admin.Delivered')}}</option>
                                        <option {{ $order->order_status == 3 ? 'selected' : '' }} value="3">{{__('admin.Completed')}}</option>
                                        <option {{ $order->order_status == 4 ? 'selected' : '' }} value="4">{{__('admin.Declined')}}</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                                    <button type="submit" class="btn btn-primary">{{__('admin.Update Status')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            paging: false,
            info: false
        });
    });
</script>

    <script>
        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url("admin/delete-order/") }}' + "/" + id)
        }

        function printTable() {
            var printContents = document.getElementById('printableTable').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
