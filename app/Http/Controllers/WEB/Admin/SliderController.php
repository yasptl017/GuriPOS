<?php
namespace App\Http\Controllers\WEB\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Setting;
use Image;
use File;
class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function slider_intro(){
        $setting = Setting::first();
        return view('admin.slider', compact('setting'));
    }

    public function index(){
        $sliders = Slider::all();
        return view('admin.gallery', compact('sliders'));
    }



    public function create(){
        return view('admin.create_slider');
    }

    public function store(Request $request){


        $slider = new Slider();
        if($request->slider_image){
            $extention = $request->slider_image->getClientOriginalExtension();
            $slider_image = 'slider'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $slider_image = 'uploads/custom-images/'.$slider_image;
            Image::make($request->slider_image)
                ->save(public_path().'/'.$slider_image);
            $slider->image = $slider_image;
        }

        $slider->save();

        $notification= trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function edit($id){
        $slider = Slider::find($id);
        return view('admin.edit_slider', compact('slider'));
    }

    public function update(Request $request, $id){
        $rules = [
            'description' => 'required',
            'status' => 'required',
            'serial' => 'required',
            'title_one' => 'required',
            'title_two' => 'required',
            'offer_text' => 'required',
            'link' => 'required',
        ];
        $customMessages = [
            'title_one.required' => trans('admin_validation.Title one is required'),
            'title_two.required' => trans('admin_validation.Title two is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'status.required' => trans('admin_validation.Status is required'),
            'serial.required' => trans('admin_validation.Serial is required'),
            'offer_text.required' => trans('admin_validation.Offer text is required'),
            'link.required' => trans('admin_validation.Link is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $slider = Slider::find($id);
        if($request->slider_image){
            $existing_slider = $slider->image;
            $extention = $request->slider_image->getClientOriginalExtension();
            $slider_image = 'slider'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $slider_image = 'uploads/custom-images/'.$slider_image;
            Image::make($request->slider_image)
                ->save(public_path().'/'.$slider_image);
            $slider->image = $slider_image;
            $slider->save();
            if($existing_slider){
                if(File::exists(public_path().'/'.$existing_slider))unlink(public_path().'/'.$existing_slider);
            }
        }

        $slider->description = $request->description;
        $slider->serial = $request->serial;
        $slider->status = $request->status;
        $slider->title_one = $request->title_one;
        $slider->title_two = $request->title_two;
        $slider->offer_text = $request->offer_text;
        $slider->link = $request->link;
        $slider->save();

        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.slider.index')->with($notification);
    }

    public function destroy($id){
        $slider = Slider::find($id);
        $existing_slider = $slider->image;
        $slider->delete();
        if($existing_slider){
            if(File::exists(public_path().'/'.$existing_slider))unlink(public_path().'/'.$existing_slider);
        }

        $notification= trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function update_slider_image(Request $request){
        $setting = Setting::first();
        if($request->background_image){
            $existing_bg = $setting->slider_background;
            $extention = $request->background_image->getClientOriginalExtension();
            $bg_image = 'slider-bg'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->background_image->move(public_path('uploads/website-images/'),$bg_image);
            $setting->slider_background = $bg_image;
            $setting->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        if($request->slider_offer_image){
            $existing_bg = $setting->slider_offer_image;
            $extention = $request->slider_offer_image->getClientOriginalExtension();
            $bg_image = 'slider-foreground1'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->slider_offer_image->move(public_path('uploads/website-images/'),$bg_image);
            $setting->slider_offer_image = $bg_image;
            $setting->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        $setting->slider_header_one = $request->slider_header_one;
        $setting->slider_header_two = $request->slider_header_two;
        $setting->slider_description = $request->slider_description;
        $setting->slider_offer_text = $request->slider_offer_text;

        $setting->save();

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
