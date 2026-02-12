<?php

namespace App\Http\Controllers\WEB\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductGallery;
use App\Models\OrderProduct;
use App\Models\ProductReview;
use App\Models\Wishlist;
use App\Models\Setting;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartVariant;
use Image;
use File;
use Str;
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = Product::query()
            ->select(['id', 'name', 'price', 'today_special'])
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $searchTerm = trim((string) $request->input('search'));
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        $products = $query->paginate(20)->appends($request->all());

        $orderedProductIds = OrderProduct::query()
            ->whereIn('product_id', $products->pluck('id'))
            ->distinct()
            ->pluck('product_id')
            ->flip()
            ->toArray();

        $setting = Setting::first();
        $frontend_url = $setting->frontend_url;
        $frontend_view = $frontend_url . 'single-product?slug=';

        if ($request->ajax()) {
            return response()->json([
                'rows' => view('admin.partials.product_table_rows', compact('products', 'orderedProductIds', 'setting'))->render(),
                'pagination' => $products->links('custom_paginator')->toHtml(),
            ]);
        }

        return view('admin.product', compact('products', 'orderedProductIds', 'setting', 'frontend_view'));
    }
    

    public function create()
    {
        $categories = Category::all();

        return view('admin.create_product',compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'slug' => 'required|unique:products',
            'thumb_image' => 'nullable|image',
            'category' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $product = new Product();
        $product->thumb_image = '';
        if($request->thumb_image){
            $extention = $request->thumb_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->thumb_image)
                ->save(public_path().'/'.$image_name);
            $product->thumb_image=$image_name;
        }

        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->category_id = $request->category;;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price;
        $product->short_description = $request->short_description ?? '';
        $product->long_description = $request->long_description ?? '';
        $product->status = $request->status;
        $product->tags = $request->tags;
        $product->seo_title = $request->seo_title ? $request->seo_title : $request->name;
        $product->seo_description = $request->seo_description ? $request->seo_description : $request->name;
        $product->today_special = $request->today_special;
        $product->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product.index')->with($notification);
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $categories = Category::all();

        return view('admin.edit_product',compact('categories','product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $rules = [
            'name' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id,
            'thumb_image' => 'nullable|image',
            'remove_thumb_image' => 'nullable|boolean',
            'category' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
            'today_special' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $removeThumbImage = $request->boolean('remove_thumb_image');

        if ($removeThumbImage && !$request->thumb_image && $product->thumb_image) {
            $old_thumbnail = $product->thumb_image;
            $product->thumb_image = '';
            $product->save();

            if (File::exists(public_path().'/'.$old_thumbnail)) {
                unlink(public_path().'/'.$old_thumbnail);
            }
        }

        if($request->thumb_image){
            $old_thumbnail = $product->thumb_image;
            $extention = $request->thumb_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->thumb_image)
                ->save(public_path().'/'.$image_name);
            $product->thumb_image=$image_name;
            $product->save();
            if($old_thumbnail){
                if(File::exists(public_path().'/'.$old_thumbnail))unlink(public_path().'/'.$old_thumbnail);
            }
        }

        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->category_id = $request->category;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price;
        $product->short_description = $request->short_description ?? '';
        $product->long_description = $request->long_description ?? '';
        $product->tags = $request->tags;
        $product->status = $request->status;
        $product->seo_title = $request->seo_title ? $request->seo_title : $request->name;
        $product->seo_description = $request->seo_description ? $request->seo_description : $request->name;
        $product->today_special = $request->today_special;
        $product->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product.index')->with($notification);
    }

    public function destroy($id)
    {
        $count = OrderProduct::where(['product_id' => $id])->count();
        if($count > 0){
            $notification = trans('admin_validation.You can not delete this item');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $product = Product::find($id);
        $gallery = $product->gallery;
        $old_thumbnail = $product->thumb_image;
        $product->delete();
        if($old_thumbnail){
            if(File::exists(public_path().'/'.$old_thumbnail))unlink(public_path().'/'.$old_thumbnail);
        }
        foreach($gallery as $image){
            $old_image = $image->image;
            $image->delete();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        ProductReview::where('product_id',$id)->delete();
        Wishlist::where('product_id',$id)->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product.index')->with($notification);
    }

    public function changeStatus($id){
        $product = Product::find($id);
        if($product->status == 1){
            $product->status = 0;
            $product->save();
            $message = trans('admin_validation.InActive Successfully');
        }else{
            $product->status = 1;
            $product->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

}
