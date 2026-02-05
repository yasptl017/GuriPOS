<?php
namespace App\Http\Controllers\WEB\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ErrorPage;
use Image;
use File;
class ErrorPageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $errorpage = ErrorPage::where('id', 1)->first();
        return view('admin.error_page', compact('errorpage'));
    }

    public function show($id){
        $errorPage = ErrorPage::find($id);
        return response()->json(['errorPage' => $errorPage], 200);
    }

    public function update(Request $request, $id)
    {
        $errorPage = ErrorPage::find($id);
        $rules = [
            'header'=>'required',
            'description'=>'required',
        ];

        $customMessages = [
            'header.required' => trans('admin_validation.Header is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];

        $this->validate($request, $rules,$customMessages);

        $errorPage->header=$request->header;
        $errorPage->description=$request->description;
        $errorPage->save();

        if($request->image){
            $existing_banner = $errorPage->image;
            $extention = $request->image->getClientOriginalExtension();
            $banner_name = 'errorpage'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/website-images/'.$banner_name;
            Image::make($request->image)
                ->save(public_path().'/'.$banner_name);
            $errorPage->image = $banner_name;
            $errorPage->save();
            if($existing_banner){
                if(File::exists(public_path().'/'.$existing_banner))unlink(public_path().'/'.$existing_banner);
            }
        }

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

}

