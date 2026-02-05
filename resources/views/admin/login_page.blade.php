@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Admin Login Page')}}</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
   <section class="section">
      <div class="section-header">
         <h1>{{__('admin.Admin Login Page')}}</h1>
      </div>
      <div class="section-body">
         <div class="row mt-4">
            <div class="col">
               <div class="card">
                  <div class="card-body">
                     <form action="{{ route('admin.update-login-page') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                           <div class="form-group col-12">
                              <label>{{__('admin.Existing Image')}}</label>
                              <div>
                                 <img src="{{ asset($login_page_image->login_page_image) }}" alt="" class="why_choose_us_background">
                              </div>
                           </div>
                           <div class="form-group col-12">
                              <label>{{__('admin.New Background Image')}}</label>
                              <input type="file" name="image" class="form-control-file">
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <button class="btn btn-primary">{{__('admin.Update')}}</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection
