@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Homepage')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Homepage')}}</h1>
          </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.update-homepage') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <h5>{{__('admin.Today Special')}}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="">{{__('admin.Existing Image')}}</label>
                                            <div>
                                                <img src="{{ asset($homepage->today_special_image) }}" alt="" class="homepage_image">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.New Image')}}</label>
                                            <input type="file" class="form-control-file" name="today_special_image">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Short Title')}}</label>
                                            <input type="text" class="form-control" value="{{ $homepage->today_special_short_title }}" name="today_special_short_title">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Long Title')}}</label>
                                            <input type="text" class="form-control" value="{{ $homepage->today_special_long_title }}" name="today_special_long_title">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Description')}}</label>
                                            <textarea name="today_special_description" class="form-control text-area-3" id="" cols="30" rows="10">{{ $homepage->today_special_description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Item')}}</label>
                                            <input type="number" class="form-control" value="{{ $homepage->today_special_item }}" name="today_special_item">
                                        </div>

                                        <div class="form-group">
                                            <div class="control-label">{{__('admin.Visibility Status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->today_special_status == 1 ? 'checked' : '' }} type="checkbox" name="today_special_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
                                          </div>
                                    </div>
                                </div>

                                <h5>{{__('admin.Menu Section')}}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Left side image')}}</label>
                                                    <div>
                                                        <img src="{{ asset($homepage->menu_left_image) }}" alt="" class="homepage_image">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="">{{__('admin.New Image')}}</label>
                                                    <input type="file" class="form-control-file" name="menu_left_image">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Right side image')}}</label>
                                                    <div>
                                                        <img src="{{ asset($homepage->menu_right_image) }}" alt="" class="homepage_image">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="">{{__('admin.New Image')}}</label>
                                                    <input type="file" class="form-control-file" name="menu_right_image">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Short Title')}}</label>
                                            <input type="text" class="form-control" value="{{ $homepage->menu_short_title }}" name="menu_short_title">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Long Title')}}</label>
                                            <input type="text" class="form-control" value="{{ $homepage->menu_long_title }}" name="menu_long_title">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Description')}}</label>
                                            <textarea name="menu_description" class="form-control text-area-3" id="" cols="30" rows="10">{{ $homepage->menu_description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Item')}}</label>
                                            <input type="number" class="form-control" value="{{ $homepage->menu_item }}" name="menu_item">
                                        </div>

                                        <div class="form-group">
                                            <div class="control-label">{{__('admin.Visibility Status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->menu_status == 1 ? 'checked' : '' }} type="checkbox" name="menu_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
                                          </div>
                                    </div>
                                </div>

                                <h5>{{__('admin.Advertisement')}}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="">{{__('admin.Item')}}</label>
                                            <input type="number" class="form-control" value="{{ $homepage->total_advertisement_item }}" name="total_advertisement_item">
                                        </div>

                                        <div class="form-group">
                                            <div class="control-label">{{__('admin.Visibility Status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->advertisement_status == 1 ? 'checked' : '' }} type="checkbox" name="advertisement_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
                                          </div>
                                    </div>
                                </div>

                                <h5>{{__('admin.Our Chef')}}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Left side image')}}</label>
                                                    <div>
                                                        <img src="{{ asset($homepage->chef_left_image) }}" alt="" class="homepage_image">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="">{{__('admin.New Image')}}</label>
                                                    <input type="file" class="form-control-file" name="chef_left_image">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Right side image')}}</label>
                                                    <div>
                                                        <img src="{{ asset($homepage->chef_right_image) }}" alt="" class="homepage_image">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="">{{__('admin.New Image')}}</label>
                                                    <input type="file" class="form-control-file" name="chef_right_image">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Short Title')}}</label>
                                            <input type="text" class="form-control" value="{{ $homepage->chef_short_title }}" name="chef_short_title">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Long Title')}}</label>
                                            <input type="text" class="form-control" value="{{ $homepage->chef_long_title }}" name="chef_long_title">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Description')}}</label>
                                            <textarea name="chef_description" class="form-control text-area-3" id="" cols="30" rows="10">{{ $homepage->chef_description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Item')}}</label>
                                            <input type="number" class="form-control" value="{{ $homepage->chef_item }}" name="chef_item">
                                        </div>

                                        <div class="form-group">
                                            <div class="control-label">{{__('admin.Visibility Status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->chef_status == 1 ? 'checked' : '' }} type="checkbox" name="chef_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
                                          </div>
                                    </div>
                                </div>

                                <h5>{{__('admin.Testimonial')}}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-12">

                                        <div class="form-group">
                                            <label for="">{{__('admin.Short Title')}}</label>
                                            <input type="text" class="form-control" value="{{ $homepage->testimonial_short_title }}" name="testimonial_short_title">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Long Title')}}</label>
                                            <input type="text" class="form-control" value="{{ $homepage->testimonial_long_title }}" name="testimonial_long_title">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Description')}}</label>
                                            <textarea name="testimonial_description" class="form-control text-area-3" id="" cols="30" rows="10">{{ $homepage->testimonial_description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Item')}}</label>
                                            <input type="number" class="form-control" value="{{ $homepage->testimonial_item }}" name="testimonial_item">
                                        </div>

                                        <div class="form-group">
                                            <div class="control-label">{{__('admin.Visibility Status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->testimonial_status == 1 ? 'checked' : '' }} type="checkbox" name="testimonial_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
                                          </div>
                                    </div>
                                </div>

                                <h5>{{__('admin.Blog')}}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-12">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Home one background image')}}</label>
                                                    <div>
                                                        <img src="{{ asset($homepage->blog_background) }}" alt="" class="w_300">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="">{{__('admin.New Image')}}</label>
                                                    <input type="file" class="form-control-file" name="blog_background">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Home2 background image')}}</label>
                                                    <div>
                                                        <img src="{{ asset($homepage->blog_background_2) }}" alt="" class="w_300">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="">{{__('admin.New Image')}}</label>
                                                    <input type="file" class="form-control-file" name="blog_background_2">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Short Title')}}</label>
                                            <input type="text" class="form-control" value="{{ $homepage->blog_short_title }}" name="blog_short_title">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Long Title')}}</label>
                                            <input type="text" class="form-control" value="{{ $homepage->blog_long_title }}" name="blog_long_title">
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Description')}}</label>
                                            <textarea name="blog_description" class="form-control text-area-3" id="" cols="30" rows="10">{{ $homepage->blog_description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="">{{__('admin.Item')}}</label>
                                            <input type="number" class="form-control" value="{{ $homepage->blog_item }}" name="blog_item">
                                        </div>

                                        <div class="form-group">
                                            <div class="control-label">{{__('admin.Visibility Status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->blog_status == 1 ? 'checked' : '' }} type="checkbox" name="blog_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
                                          </div>
                                    </div>
                                </div>

                                <h5>{{__('admin.Others')}}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="control-label">{{__('admin.Mobile app visibility status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->mobile_app_status == 1 ? 'checked' : '' }} type="checkbox" name="mobile_app_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <div class="control-label">{{__('admin.Counter visibility status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->counter_status == 1 ? 'checked' : '' }} type="checkbox" name="counter_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <div class="control-label">{{__('admin.About us visibility status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->why_choose_us_status == 1 ? 'checked' : '' }} type="checkbox" name="why_choose_us_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
                                        </div>

                                         <div class="form-group">
                                            <div class="control-label">{{__('admin.Video visibility status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->video_section_status == 1 ? 'checked' : '' }} type="checkbox" name="video_section_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <div class="control-label">{{__('admin.Service visibility status')}}</div>
                                            <label class="custom-switch mt-2">
                                              <input {{ $homepage->service_status == 1 ? 'checked' : '' }} type="checkbox" name="service_status" class="custom-switch-input">
                                              <span class="custom-switch-indicator"></span>
                                              <span class="custom-switch-description">{{__('admin.Please enable or disable this section')}}</span>
                                            </label>
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
        </section>
      </div>

@endsection
