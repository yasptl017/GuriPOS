@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Counter')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Counter')}}</h1>
          </div>
          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.update-counter-image') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="form-group">
                            <label for="">{{__('admin.Counter Background')}}</label>
                            <div>
                              <img src="{{ asset($setting->counter_background) }}" alt="" class="w_300">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="">{{__('admin.New Background')}}</label>
                            <input type="file" name="background_image" class="form-control-file">
                          </div>

                          <button type="submit" class="btn btn-success">{{__('admin.Update')}}</button>
                        </form>

                    </div>
                  </div>
                </div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.counter.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{__('admin.Title')}}</th>
                                    <th>{{__('admin.Quantity')}}</th>
                                    <th>{{__('admin.Icon')}}</th>
                                    <th>{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($counters as $index => $counter)
                                    <tr>
                                        <td>{{ $counter->title }}</td>
                                        <td>{{ $counter->quantity }}</td>
                                        <td>{{ $counter->icon }}</td>

                                        <td>
                                        <a href="{{ route('admin.counter.edit',$counter->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $counter->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
        $("#deleteForm").attr("action",'{{ url("admin/counter/") }}'+"/"+id)
    }
</script>
@endsection
