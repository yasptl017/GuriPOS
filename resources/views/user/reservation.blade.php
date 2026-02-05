@extends('layout')
@section('title')
    <title>{{__('user.Reservation')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Reservation')}}">
@endsection

@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Reservation')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:;">{{__('user.Reservation')}}</a></li>
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
                            <div class="tf_dashboard_body dashboard_review">
                                <h3>{{__('user.Reservation')}}</h3>
                                <div class="tf_dashboard_order dashboard_reservation">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr class="t_header">
                                                    <th class="sn">{{__('user.SN')}}</th>
                                                    <th class="time">{{__('user.Date & Time')}}</th>
                                                    <th class="person">{{__('user.Person')}}</th>
                                                    <th class="status">{{__('user.Status')}}</th>
                                                </tr>
                                                @foreach ($reservations as $index => $reservation)
                                                    <tr>
                                                        <td class="sn">
                                                            <h5>#{{ ++$index }}</h5>
                                                        </td>
                                                        <td class="time">
                                                            <p>{{ date('d M, Y', strtotime($reservation->reserve_date)) }}</p>
                                                            <br>
                                                            <p>{{ $reservation->reserve_time }}</p>

                                                        </td>
                                                        <td class="person">
                                                            {{ $reservation->person_qty }}
                                                        </td>
                                                        <td class="status">
                                                            @if ($reservation->reserve_status == 1)
                                                            <span class="complete">{{__('user.Approved')}} </span>
                                                            @elseif ($reservation->reserve_status == 3)
                                                            <span class="complete">{{__('user.Completed')}} </span>
                                                            @elseif ($reservation->reserve_status == 4)
                                                            <span class="cancel">{{__('user.Declined')}} </span>
                                                            @else
                                                            <span class="cancel">{{__('user.Pending')}}</span>
                                                            @endif
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
            </div>
        </div>
    </section>

@endsection
