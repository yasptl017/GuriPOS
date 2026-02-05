<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerImage;
use App\Models\ShopPage;
use App\Models\Product;
use App\Models\Category;
use Image;
use File;
class AdvertisementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $banners = BannerImage::whereIn('id', [1,2])->get();
        $cart_banner = BannerImage::where('id', 5)->first();
        return view('admin.advertisement')->with(['banners' => $banners, 'cart_banner' => $cart_banner]);
    }


    public function store(Request $request){
        $rules = [
            'title' => 'required',
            'title2' => 'required',
            'description' => 'required',
            'banner_image' => 'required',
            'status' => 'required',
            'link' => 'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title2.required' => trans('admin_validation.Title is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'banner_image.required' => trans('admin_validation.Image is required'),
            'status.required' => trans('admin_validation.Status is required'),
            'serial.required' => trans('admin_validation.Serial is required'),
            'link.required' => trans('admin_validation.Link is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $new_advertisement = new BannerImage();

        if($request->banner_image){
            $extention = $request->banner_image->getClientOriginalExtension();
            $banner_name = 'advertisement'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/custom-images/'.$banner_name;
            Image::make($request->banner_image)
                ->save(public_path().'/'.$banner_name);
            $new_advertisement->image = $banner_name;
        }

        $new_advertisement->title = $request->title;
        $new_advertisement->description = $request->description;
        $new_advertisement->status = $request->status;
        $new_advertisement->link = $request->link;
        $new_advertisement->save();

        $notification= trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id){

        if($id == 5){
            $new_advertisement = BannerImage::find($id);

            if($request->banner_image){
                $existing_image = $new_advertisement->image;
                $extention = $request->banner_image->getClientOriginalExtension();
                $banner_name = 'advertisement'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
                $banner_name = 'uploads/custom-images/'.$banner_name;
                Image::make($request->banner_image)
                    ->save(public_path().'/'.$banner_name);
                $new_advertisement->image = $banner_name;
                $new_advertisement->save();
                if($existing_image){
                    if(File::exists(public_path().'/'.$existing_image))unlink(public_path().'/'.$existing_image);
                }
            }

            $new_advertisement->link = $request->link;
            $new_advertisement->save();

            $notification= trans('admin_validation.Updated Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);
        }

        $rules = [
            'title' => 'required',
            'title2' => 'required',
            'description' => 'required',
            'link' => 'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title2.required' => trans('admin_validation.Title is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'status.required' => trans('admin_validation.Status is required'),
            'serial.required' => trans('admin_validation.Serial is required'),
            'link.required' => trans('admin_validation.Link is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $new_advertisement = BannerImage::find($id);

        if($request->banner_image){
            $existing_image = $new_advertisement->image;
            $extention = $request->banner_image->getClientOriginalExtension();
            $banner_name = 'advertisement'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/custom-images/'.$banner_name;
            Image::make($request->banner_image)
                ->save(public_path().'/'.$banner_name);
            $new_advertisement->image = $banner_name;
            $new_advertisement->save();
            if($existing_image){
                if(File::exists(public_path().'/'.$existing_image))unlink(public_path().'/'.$existing_image);
            }
        }

        $new_advertisement->title = $request->title;
        $new_advertisement->description = $request->description;
        $new_advertisement->title2 = $request->title2;
        $new_advertisement->link = $request->link;
        $new_advertisement->save();

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function destroy($id){
        $banner = BannerImage::find($id);
        $existing_image = $banner->image;
        $banner->delete();
        if($existing_image){
            if(File::exists(public_path().'/'.$existing_image))unlink(public_path().'/'.$existing_image);
        }

        $notification= trans('admin_validation.Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}
