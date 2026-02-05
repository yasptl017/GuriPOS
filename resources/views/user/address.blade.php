@extends('layout')
@section('title')
    <title>{{__('user.Address')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Address')}}">
@endsection

@section('public-content')


    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Address')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:;">{{__('user.Address')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


        <!--=========================
        DASHBOARD START
    ==========================-->
    <section class="tf__dashboard mt_120 xs_mt_90 mb_100 xs_mb_70">
        <div class="container">
            <div class="tf__dashboard_area">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__dashboard_menu">
                            <div class="dasboard_header">
                                <div class="dasboard_header_img">

                                    @if ($personal_info->image)

                                    <img id="preview-user-avatar" src="{{ asset($personal_info->image) }}" alt="user" class="img-fluid w-100">
                                    @else
                                    <img id="preview-user-avatar" src="{{ asset($default_user_avatar) }}" alt="user" class="img-fluid w-100">
                                    @endif

                                    <label for="upload"><i class="far fa-camera"></i></label>
                                    <form id="upload_user_avatar_form" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <input type="file" name="image" id="upload" hidden onchange="previewThumnailImage(event)">
                                    </form>
                                </div>
                                <h2>{{ html_decode($personal_info->name) }}</h2>
                            </div>

                            @include('user.sidebar')

                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__dashboard_content">
                            <div class="tf_dashboard_body">
                                <h3>{{__('user.Address')}}

                                    <a class="dash_add_new_address" href="{{ route('address.create') }}">{{__('user.add new')}}</a>
                                </h3>

                                <div class="tf_dashboard_address">
                                    <div class="tf_dashboard_existing_address">
                                        <div class="row">
                                            @foreach ($addresses as $address)
                                                <div class="col-md-6">
                                                    <div class="tf__checkout_single_address ">
                                                        <div class="form-check address-list-{{ $address->id }}">
                                                            <label class="form-check-label">
                                                                @if ($address->type == 'home')
                                                                <span class="icon"><i class="fas fa-home"></i>{{__('user.Home')}}</span>
                                                                @else
                                                                <span class="icon"><i class="far fa-car-building"></i>{{__('user.Office')}}</span>
                                                                @endif
                                                                <span class="address">{{__('user.Name')}} : {{ $address->first_name.' '. $address->last_name }}</span>

                                                                <span class="address">{{__('user.Phone')}} : {{ $address->phone }}</span>
                                                                                                                        <span class="address">{{__('user.Address')}} : {{ $address->address }}</span>
                                                            </label>
                                                        </div>
                                                        <ul>
                                                            <li><a href="{{ route('address.edit', $address->id) }}"  class="dash_edit_btn"><i
                                                                        class="far fa-edit"></i></a></li>
                                                            <li><a onclick="delete_address({{ $address->id }})" class="dash_del_icon"><i
                                                                        class="fas fa-trash-alt"></i></a>
                                                            </li>

                                                            <form id="delete_address_{{ $address->id }}" action="{{ route('address.destroy', $address->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            </form>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        function delete_address(id){
            Swal.fire({
                title: "{{__('user.Are you realy want to delete this item ?')}}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('user.Yes, Delete It')}}",
                cancelButtonText: "{{__('user.Cancel')}}",
            }).then((result) => {
                if (result.isConfirmed) {

                    var isDemo = "{{ env('APP_MODE') }}"
                    $("#delete_address_"+id).submit();
                }

            })
        }



    </script>
@endsection
