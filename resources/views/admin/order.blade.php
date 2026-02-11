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
            <div class="row mb-3">
                <div class="col-md-12">
                    <ul class="nav nav-pills">
                        <li class="nav-item mr-2">
                            <a class="nav-link {{ $orderTypeFilter === '' ? 'active' : '' }}" href="{{ route('admin.web-order') }}">All</a>
                        </li>
                        <li class="nav-item mr-2">
                            <a class="nav-link {{ $orderTypeFilter === 'pickup' ? 'active' : '' }}" href="{{ route('admin.web-order', ['order_type' => 'pickup']) }}">Pickup</a>
                        </li>
                        <li class="nav-item mr-2">
                            <a class="nav-link {{ $orderTypeFilter === 'delivery' ? 'active' : '' }}" href="{{ route('admin.web-order', ['order_type' => 'delivery']) }}">Delivery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $orderTypeFilter === 'dine_in' ? 'active' : '' }}" href="{{ route('admin.web-order', ['order_type' => 'dine_in']) }}">Dine-in</a>
                        </li>
                    </ul>
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
                                                        <a href="{{ route('admin.order-show', ['id' => $order->id, 'source' => 'web']) }}" class="btn btn-primary btn-sm" title="View Order">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-info btn-sm"
                                                                onclick="viewReceipt({{ $order->id }})"
                                                                title="View Receipt">
                                                            <i class="fas fa-receipt"></i>
                                                        </button>
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

    <!-- Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-receipt text-info mr-2"></i>Print Receipt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <pre id="receiptContent" style="font-family: 'Courier New', monospace; font-size: 13px; background:#f8f9fa; padding: 16px; margin:0; white-space: pre-wrap; max-height: 70vh; overflow-y: auto;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="printReceiptFromModal()">
                        <i class="fas fa-print mr-1"></i> Print to Printer
                    </button>
                    <button type="button" class="btn btn-primary" onclick="browserPrintReceipt()">
                        <i class="fas fa-print mr-1"></i> Print (Browser)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url("admin/delete-order/") }}' + "/" + id)
        }

        var _receiptOrderId = null;

        function viewReceipt(orderId) {
            _receiptOrderId = orderId;
            $('#receiptContent').text('Loading…');
            $('#receiptModal').modal('show');
            $.ajax({
                url: '{{ url("admin/order-receipt") }}/' + orderId,
                type: 'GET',
                success: function(response) {
                    if (response.receipt) {
                        $('#receiptContent').text(response.receipt);
                    } else {
                        $('#receiptContent').text('No receipt saved yet.\nPrint the order first to generate a receipt.');
                    }
                },
                error: function() {
                    $('#receiptContent').text('Error loading receipt.');
                }
            });
        }

        function printReceiptFromModal() {
            if (!_receiptOrderId) return;
            $.ajax({
                url: '{{ url("admin/order-print") }}/' + _receiptOrderId,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    toastr.success('Sent to printer successfully');
                },
                error: function() {
                    toastr.error('Failed to send to printer');
                }
            });
        }

        function browserPrintReceipt() {
            var content = $('#receiptContent').text();
            var w = window.open('', '_blank', 'width=400,height=600');
            w.document.write('<html><head><title>Receipt</title><style>body{font-family:monospace;font-size:13px;white-space:pre;margin:10px;}</style></head><body>' + content.replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</body></html>');
            w.document.close();
            w.focus();
            w.print();
        }
    </script>
@endsection
