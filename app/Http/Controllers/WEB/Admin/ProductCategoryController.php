<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PopularCategory;
use App\Models\FeaturedCategory;
use App\Models\MegaMenuSubCategory;
use App\Models\MegaMenuCategory;
use Illuminate\Http\Request;
use Image;
use File;
use Str;
class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $categories = Category::with('products')->get();
        return view('admin.product_category',compact('categories'));
    }

    public function create()
    {
        return view('admin.create_product_category');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'=>'required|unique:categories',
            'slug'=>'required|unique:categories',
            'status'=>'required',
            'show_homepage'=>'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
        ];
        $this->validate($request, $rules,$customMessages);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->show_homepage = $request->show_homepage;
        $category->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product-category.index')->with($notification);
    }


    public function show($id){
        $category = Category::find($id);
        return response()->json(['category' => $category],200);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.edit_product_category',compact('category'));
    }


    public function update(Request $request,$id)
    {
        $category = Category::find($id);
        $rules = [
            'name'=>'required|unique:categories,name,'.$category->id,
            'slug'=>'required|unique:categories,name,'.$category->id,
            'status'=>'required',
            'show_homepage'=>'required',
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
        ];
        $this->validate($request, $rules,$customMessages);

        $category->show_homepage = $request->show_homepage;
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product-category.index')->with($notification);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product-category.index')->with($notification);
    }

    public function changeStatus($id){
        $category = Category::find($id);
        if($category->status==1){
            $category->status=0;
            $category->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $category->status=1;
            $category->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
