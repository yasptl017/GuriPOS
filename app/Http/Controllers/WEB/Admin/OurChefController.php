<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OurChef;
use Image;
use File;
use Str;
use Cache;

class OurChefController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $chef_list = OurChef::orderBy('id','desc')->get();

        return view('admin.chef', compact('chef_list'));
    }

    public function create()
    {
        return view('admin.create_chef');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'designation' => 'required',
            'image' => 'required',
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'designation.required' => trans('admin_validation.Designation is required'),
            'image.required' => trans('admin_validation.Image is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $chef = new OurChef();

        if($request->image){
            $extention = $request->image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Ymdhis').'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->image)->save(public_path().'/'.$image_name);
        }

        $chef->name = $request->name;
        $chef->designation = $request->designation;
        $chef->image = $image_name;
        $chef->facebook = $request->facebook;
        $chef->twitter = $request->twitter;
        $chef->instagram = $request->instagram;
        $chef->linkedin = $request->linkedin;
        $chef->status = $request->status;
        $chef->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.our-chef.index')->with($notification);
    }

    public function edit($id)
    {
        $chef = OurChef::find($id);
        return view('admin.edit_chef',compact('chef'));
    }

    public function update(Request $request, $id)
    {
        $chef = OurChef::find($id);
        $rules = [
            'name' => 'required',
            'designation' => 'required',
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'designation.required' => trans('admin_validation.Designation is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        if($request->image){
            $existing_image = $chef->image;
            $extention = $request->image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Ymdhis').'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->image)
                    ->save(public_path().'/'.$image_name);
            $chef->image= $image_name;
            $chef->save();
            if($existing_image){
                if(File::exists(public_path().'/'.$existing_image))unlink(public_path().'/'.$existing_image);
            }
        }

        $chef->name = $request->name;
        $chef->designation = $request->designation;
        $chef->facebook = $request->facebook;
        $chef->twitter = $request->twitter;
        $chef->instagram = $request->instagram;
        $chef->linkedin = $request->linkedin;
        $chef->status = $request->status;

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.our-chef.index')->with($notification);
    }


    public function destroy($id)
    {
        $chef = OurChef::find($id);
        $existing_image = $chef->image;
        $chef->delete();

        if($existing_image){
            if(File::exists(public_path().'/'.$existing_image))unlink(public_path().'/'.$existing_image);
        }

        $notification = trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.our-chef.index')->with($notification);
    }

    public function changeStatus($id){
        $item = OurChef::find($id);
        if($item->status == 1){
            $item->status = 0;
            $item->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $item->status = 1;
            $item->save();
            $message = trans('admin_validation.Active Successfully');
        }

        return response()->json($message);
    }
}
