@extends('admin.master_layout')
@section('title')
    <title>{{__('admin.Delivery Area')}}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{__('admin.Delivery Area')}}</h1>

            </div>

            <div class="section-body">
                <a href="{{ route('admin.delivery-area.create') }}" class="btn btn-primary"><i
                        class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
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
                                            <th>{{__('admin.Delivery Time')}}</th>
                                            <th>{{__('admin.Delivery Fee')}}</th>
                                            <th>{{__('admin.Status')}}</th>
                                            <th>{{__('admin.Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($areas as $index => $area)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $area->min_range }}km - {{$area->max_range}}km</td>
                                                <td>{{ $area->min_time.' - '. $area->max_time }} {{__('admin.Minutes')}}</td>
                                                <td>{{ $currency_icon }}{{ $area->delivery_fee }}</td>
                                                <td>
                                                    @if ($area->status == 1)

                                                        <span class="badge badge-success">{{__('admin.Active')}}</span>

                                                    @else
                                                        <span class="badge badge-danger">{{__('admin.Active')}}</span>

                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.delivery-area.edit',$area->id) }}"
                                                       class="btn btn-primary btn-sm"><i class="fa fa-edit"
                                                                                         aria-hidden="true"></i></a>
                                                    <a href="javascript:" data-toggle="modal"
                                                       data-target="#deleteModal" class="btn btn-danger btn-sm"
                                                       onclick="deleteData({{ $area->id }})"><i class="fa fa-trash"
                                                                                                aria-hidden="true"></i></a>
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

    <!-- Modal -->
    <div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    {{__('admin.You can not delete this category. Because there are one or more blogs has been created in this category.')}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url("admin/delivery-area/") }}' + "/" + id)
        }

    </script>
@endsection
