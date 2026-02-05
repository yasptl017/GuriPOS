@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Gallery')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Gallery')}}</h1>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.slider.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{__('admin.Image')}}</th>
                                    <th>{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($sliders as $index => $slider)
                                    <tr>
                                        <td>
                                            <img src="{{ asset($slider->image) }}" width="100px" alt="" class="py-1">
                                        </td>
                                        <td>

                                        <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $slider->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
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

<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/slider/") }}'+"/"+id)
    }
</script>
@endsection
