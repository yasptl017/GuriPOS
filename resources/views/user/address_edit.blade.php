@extends('layout')
@section('title')
    <title>{{__('user.Edit Address')}}</title>
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
                    <h1>{{__('user.Edit Address')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:;">{{__('user.Edit Address')}}</a></li>
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
                                <h3>{{__('user.Edit Address')}}

                                    <a class="dash_add_new_address" href="{{ route('address.index') }}">{{__('user.Go Back')}}</a>
                                </h3>

                                <div class="tf_dashboard_address">
                                    <div class="tf_dashboard_new_address ">
                                        <form action="{{ route('address.update', $address->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">

                                                <div class="col-12">
                                                    <div class="tf__check_single_form">
                                                        <select name="delivery_area_id" class="select2">
                                                            <option value="">{{__('user.Select Delivery Area')}}</option>
                                                            @foreach ($delivery_areas as $delivery_area)
                                                                <option {{ $address->delivery_area_id == $delivery_area->id ? 'selected' : '' }} value="{{ $delivery_area->id }}">{{ $delivery_area->area_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-lg-12 col-xl-6">
                                                    <div class="tf__check_single_form">
                                                        <input type="text" placeholder="{{__('user.First Name')}}*" name="first_name" value="{{ $address->first_name }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-12 col-xl-6">
                                                    <div class="tf__check_single_form">
                                                        <input type="text" placeholder="{{__('user.Last Name')}} *" name="last_name" value="{{ $address->last_name }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-lg-12 col-xl-6">
                                                    <div class="tf__check_single_form">
                                                        <input type="text" placeholder="{{__('user.Phone')}}" name="phone" value="{{ $address->phone }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-12 col-xl-6">
                                                    <div class="tf__check_single_form">
                                                        <input type="email" placeholder="{{__('user.Email')}}" name="email" value="{{ $address->email }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-12">
                                                    <div class="tf__check_single_form">
                                                        <input type="text" id="address-input" placeholder="Enter address">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-12">
                                                    <div class="tf__check_single_form">
                                                        <textarea name="address" cols="3" rows="4"
                                                            placeholder="{{__('user.Address')}} *" id="selected-address">{{ $address->address }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="tf__check_single_form check_area">
                                                        <div class="form-check">
                                                            <input {{ $address->type == 'home' ? 'checked' : '' }} value="home" class="form-check-input" type="radio"
                                                                name="address_type" id="flexRadioDefault1">
                                                            <label class="form-check-label"
                                                                for="flexRadioDefault1">
                                                                {{__('user.home')}}
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input {{ $address->type == 'office' ? 'checked' : '' }} value="office" class="form-check-input" type="radio"
                                                                name="address_type" id="flexRadioDefault2">
                                                            <label class="form-check-label"
                                                                for="flexRadioDefault2">
                                                                {{__('user.office')}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">

                                                    <button type="submit" class="common_btn">{{__('user.Update Address')}}</button>
                                                </div>
                                            </div>
                                        </form>
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
        /**
         * @license
         * Copyright 2024 Google LLC. All Rights Reserved.
         * SPDX-License-Identifier: Apache-2.0
         */
        async function initAutocomplete() {
          // Initialize the Autocomplete service for the first input
          const addressInput = document.getElementById("address-input");
          const selectedAddress = document.getElementById("selected-address");
          const autocomplete = new google.maps.places.Autocomplete(addressInput);
          autocomplete.setFields(['address_components', 'formatted_address', 'name']);
          autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            selectedAddress.textContent = place.formatted_address;
          });

        }
      </script>
      <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2EqH1cqg0L0yTJ86hiGsr_ZAfEl1khss&libraries=places&callback=initAutocomplete" async defer></script>
@endsection
