@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Appointment')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Appointment')}}</h1>
          </div>
          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.update-appointment-bg') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="form-group">
                            <label for="">{{__('admin.Appointment Background')}}</label>
                            <div>
                              <img src="{{ asset($setting->appointment_bg) }}" alt="" class="w_300">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="">{{__('admin.New Background')}}</label>
                            <input type="file" name="appointment_bg" class="form-control-file">
                          </div>

                          <button type="submit" class="btn btn-success">{{__('admin.Update')}}</button>
                        </form>

                    </div>
                  </div>
                </div>
            </div>
          </div>

        </section>
      </div>


@endsection
