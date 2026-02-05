@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Product Gallery')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Product Gallery')}}</h1>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Products')}}</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-header">
                        <h1>{{__('admin.Product')}} : {{ $product->name }}</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.store-product-gallery') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">{{__('admin.New Image (Multiple)')}}</label>
                                <input type="file" class="form-control-file" name="images[]" multiple>
                            </div>
                            <input type="hidden" name="product_id" required value="{{ $product->id }}">
                            <button type="submit" class="btn btn-primary">{{__('admin.Upload')}}</button>
                        </form>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{__('admin.Image')}}</th>
                                    <th>{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($gallery as $index => $image)
                                    <tr>
                                        <td>
                                          <img class="p-2" src="{{ asset($image->image) }}" alt="" width="200px">
                                        </td>
                                        <td>
                                          <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $image->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
        $("#deleteForm").attr("action",'{{ url("admin/delete-product-image/") }}'+"/"+id)
    }
</script>
@endsection
