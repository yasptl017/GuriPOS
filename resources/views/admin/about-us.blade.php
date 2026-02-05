@extends('admin.master_layout')
@section('title')
<title>{{__('admin.About Us')}}</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
<section class="section">
   <div class="section-header">
      <h1>{{__('admin.About Us')}}</h1>
   </div>
   <div class="section-body">
      <div class="row mt-4">
         <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-3">
                            <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">


                                <li class="nav-item border rounded mb-1">
                                    <a class="nav-link active" id="paypal-tab" data-toggle="tab" href="#paypalTab" role="tab" aria-controls="paypalTab" aria-selected="true">{{__('admin.About Us')}}</a>
                                </li>

                                <li class="nav-item border rounded mb-1">
                                    <a class="nav-link" id="video-tab" data-toggle="tab" href="#videoTab" role="tab" aria-controls="videoTab" aria-selected="true">{{__('admin.Vision & Mission')}}</a>
                                </li>

                                <li class="nav-item border rounded mb-1">
                                    <a class="nav-link" id="stripe-tab" data-toggle="tab" href="#stripeTab" role="tab" aria-controls="stripeTab" aria-selected="true">{{__('admin.Why Choose Us')}}</a>
                                </li>



                            </ul>
                        </div>

                        <div class="col-12 col-sm-12 col-md-9">
                            <div class="border rounded">
                                <div class="tab-content no-padding" id="settingsContent">

                                    <div class="tab-pane fade show active" id="paypalTab" role="tabpanel" aria-labelledby="paypal-tab">
                                        <div class="card m-0">
                                            <div class="card-body">
                                                <form action="{{ route('admin.about-us.update', $about_us->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Existing Image')}}</label>
                                                        <div>
                                                            <img src="{{ asset($about_us->about_us_image) }}" alt="" class="why_choose_us_background">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.New Image')}}</label>
                                                        <input type="file" name="about_us_image" class="form-control-file">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Experience year')}}</label>
                                                        <input type="text" name="experience_year" class="form-control" value="{{ $about_us->experience_year }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Experience text')}}</label>
                                                        <input type="text" name="experience_text" class="form-control" value="{{ $about_us->experience_text }}">
                                                    </div>



                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Author name')}}</label>
                                                        <input type="text" name="author_name" class="form-control" value="{{ $about_us->author_name }}">
                                                    </div>



                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Author comment')}}</label>
                                                        <input type="text" name="author_comment" class="form-control" value="{{ $about_us->author_comment }}">
                                                    </div>



                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Short title')}}</label>
                                                        <input type="text" name="about_short_title" class="form-control" value="{{ $about_us->about_us_short_title }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Long title')}}</label>
                                                        <input type="text" name="about_long_title" class="form-control" value="{{ $about_us->about_us_long_title }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.About Us')}}</label>
                                                        <textarea name="about_us" class="form-control text-area-5" id="" cols="30" rows="10">{{ $about_us->about_us }}</textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Item one title')}}</label>
                                                        <input type="text" name="item1_title" class="form-control" value="{{ $about_us->item1_title }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Item one description')}}</label>
                                                        <input type="text" name="item1_description" class="form-control" value="{{ $about_us->item1_description }}" required>
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Item two title')}}</label>
                                                        <input type="text" name="item2_title" class="form-control" value="{{ $about_us->item2_title }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Item two description')}}</label>
                                                        <input type="text" name="item2_description" class="form-control" value="{{ $about_us->item2_description }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Item three title')}}</label>
                                                        <input type="text" name="item3_title" class="form-control" value="{{ $about_us->item3_title }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Item three description')}}</label>
                                                        <input type="text" name="item3_description" class="form-control" value="{{ $about_us->item3_description }}" required>
                                                    </div>


                                                    <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="videoTab" role="tabpanel" aria-labelledby="video-tab">
                                        <div class="card m-0">
                                            <div class="card-body">
                                                <form action="{{ route('admin.video-update', $about_us->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Background Image')}}</label>
                                                        <div>
                                                            <img src="{{ asset($about_us->vision_bg) }}" alt="" class="w_300">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.New Image')}}</label>
                                                        <input type="file" name="vision_bg" class="form-control-file">
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Vision title')}}</label>
                                                        <input type="text" name="vision_title" class="form-control" value="{{ $about_us->vision_title }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Vision description')}}</label>
                                                        <input type="text" name="vision_description" class="form-control" value="{{ $about_us->vision_description }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Mission title')}}</label>
                                                        <input type="text" name="mission_title" class="form-control" value="{{ $about_us->mission_title }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Mission description')}}</label>
                                                        <input type="text" name="mission_description" class="form-control" value="{{ $about_us->mission_description }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Goal title')}}</label>
                                                        <input type="text" name="goal_title" class="form-control" value="{{ $about_us->goal_title }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Goal description')}}</label>
                                                        <input type="text" name="goal_description" class="form-control" value="{{ $about_us->goal_description }}" required>
                                                    </div>



                                                    <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="stripeTab" role="tabpanel" aria-labelledby="stripe-tab">
                                        <div class="card m-0">
                                            <div class="card-body">
                                                <form action="{{ route('admin.why-choose-us.update', $about_us->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Background Image')}}</label>
                                                        <div>
                                                            <img src="{{ asset($about_us->why_choose_us_background) }}" alt="" class="why_choose_us_background">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.New Image')}}</label>
                                                        <input type="file" name="why_choose_us_background" class="form-control-file">
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Short title')}}</label>
                                                        <input type="text" name="why_choose_us_short_title" class="form-control" value="{{ $about_us->why_choose_us_short_title }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Long title')}}</label>
                                                        <input type="text" name="why_choose_us_long_title" class="form-control" value="{{ $about_us->why_choose_us_long_title }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Description')}}</label>
                                                        <textarea name="why_choose_us_description" class="form-control text-area-5" id="" cols="30" rows="10">{{ $about_us->why_choose_us_description }}</textarea>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Title One')}}</label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $about_us->title_one }}" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Description One')}}</label>
                                                                <input type="text" name="description_one" class="form-control " value="{{ $about_us->description_one }}" autocomplete="off">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Title Two')}}</label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $about_us->title_two }}" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Description Two')}}</label>
                                                                <input type="text" name="description_two" class="form-control " value="{{ $about_us->description_two }}" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Title Three')}}</label>
                                                                <input type="text" name="title_three" class="form-control" value="{{ $about_us->title_three }}" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Description Three')}}</label>
                                                                <input type="text" name="description_three" class="form-control " value="{{ $about_us->description_three }}" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Title Four')}}</label>
                                                                <input type="text" name="title_four" class="form-control" value="{{ $about_us->title_four }}" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Description Four')}}</label>
                                                                <input type="text" name="description_four" class="form-control " value="{{ $about_us->description_four }}" autocomplete="off">
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>

                                                </form>
                                            </div>
                                        </div>
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
</div>
@endsection
