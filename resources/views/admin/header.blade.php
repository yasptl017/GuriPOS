@php
    $setting = App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="shortcut icon"  href="{{ asset($setting->favicon) }}"  type="image/x-icon">
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  @yield('title')
  <title>{{__('admin.Login')}}</title>


  <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
  <link href="{{ asset('backend/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('backend/fontawesome/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-social.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/components.css') }}">
  @if ($setting->text_direction == 'rtl')
    <link rel="stylesheet" href="{{ asset('backend/css/rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/dev_rtl.css') }}">
    @endif
  <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/bootstrap4-toggle.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/dev.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/tagify.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-tagsinput.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/fontawesome-iconpicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/clockpicker/dist/bootstrap-clockpicker.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/datetimepicker/jquery.datetimepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/css/iziToast.min.css') }}">

  <script src="{{ asset('backend/js/jquery-3.7.0.min.js') }}"></script>

  <script src="{{ asset('backend/minicolor/jquery.minicolors.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('backend/minicolor/jquery.minicolors.css') }}">

<style>
    .fade.in {
        opacity: 1 !important;
    }

    .tox .tox-promotion,
    .tox-statusbar__branding{
        display: none !important;
    }

</style>

<style>
    dl {
      margin: 20px 0;
    }
    dt {
      font-size: 120%;
    }
    dd {
      padding: 10px 20px 20px 20px;
    }
    dd:last-child {
      border-bottom: none;
    }
    code {
      color: black;
      border: none;
      background: rgba(128, 128, 128, .1);
    }
    [dir=rtl] code {
      direction: ltr;
    }
    pre {
      background: #f8f8f8;
      border: none;
      color: #333;
      padding: 20px;
    }
    [dir=rtl] pre {
      direction: ltr;
    }
    h2 {
      margin-top: 50px;
    }
    h3 {
      color: #aaa;
    }
    .jumbotron {
      padding: 40px;
    }
    .jumbotron h1 {
      margin-top: 0;
    }
    .jumbotron p:last-child {
      margin-bottom: 0;
    }

    .minicolors-theme-bootstrap .minicolors-input {
      padding-left: 44px !important;
    }

    .minicolors-theme-bootstrap .minicolors-swatch {
        top: 7px !important;
    }

  </style>

</head>
