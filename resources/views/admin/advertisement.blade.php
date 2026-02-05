@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Advertisement')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Advertisement')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Advertisement')}}</div>
            </div>
          </div>

          <div class="section-body">
                <div class="row">
                    <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="header_title">{{__('admin.Homepage banner')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin.Title')}}</th>
                                            <th>{{__('admin.Description')}}</th>
                                            <th>{{__('admin.Image')}}</th>
                                            <th>{{__('admin.Status')}}</th>
                                            <th>{{__('admin.Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($banners as $index => $banner)
                                            <tr >

                                                <td>{{ $banner->title }}</td>
                                                <td>{{ $banner->description }}</td>
                                                <td>
                                                    <a href="{{ $banner->link }}" target="_blank">
                                                        <img src="{{ asset($banner->image) }}" alt="" class="w_150 py-2">
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($banner->status == 1)
                                                        <span class="badge badge-success">{{__('admin.Active')}}</span>
                                                    @else
                                                        <span class="badge badge-danger">{{__('admin.Inactive')}}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript:;" data-toggle="modal" data-target="#editAdvertisement-{{ $banner->id }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>


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
          </div>

          <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="header_title">{{__('admin.Cart page banner')}}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.update-advertisement', $cart_banner->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="">{{__('admin.Existing Image')}}</label>
                                    <div>
                                        <img src="{{ asset($cart_banner->image) }}" alt="" class="w_150">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('admin.New Image')}}</label>
                                    <input type="file" name="banner_image" class="form-control-file">
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('admin.Link')}}</label>
                                    <input type="text" name="link" class="form-control" value="{{ $cart_banner->link }}">
                                </div>


                                <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </section>
      </div>


      @foreach ($banners as $banner)
      <div class="modal fade" id="editAdvertisement-{{ $banner->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title">{{__('admin.Edit Banner')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="{{ route('admin.update-advertisement', $banner->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="">{{__('admin.Existing Image')}}</label>
                                <div>
                                    <img src="{{ asset($banner->image) }}" alt="" class="w_150">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.New Image')}}</label>
                                <input type="file" name="banner_image" class="form-control-file">
                            </div>



                            <div class="form-group">
                                <label for="">{{__('admin.Title')}}</label>
                                <input type="text" name="title" class="form-control" value="{{ $banner->title }}">
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.Title Two')}}</label>
                                <input type="text" name="title2" class="form-control" value="{{ $banner->title2 }}">
                            </div>



                            <div class="form-group">
                                <label for="">{{__('admin.Description')}}</label>
                                <textarea name="description" class="form-control text-area-3" id="" cols="30" rows="10">{{ $banner->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.Link')}}</label>
                                <input type="text" name="link" class="form-control" value="{{ $banner->link }}">
                            </div>



                            <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>

                        </form>
                    </div>
                </div>

            </div>
        </div>
      </div>
      @endforeach


<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/advertisement-delete/") }}'+"/"+id)
    }
</script>
@endsection
