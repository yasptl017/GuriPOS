<?php
namespace App\Http\Controllers\WEB\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
class ProductVariantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index($productId)
    {
        $product = Product::find($productId);
        if($product){
            $size_variant = array();
            $optional_item = array();
            $db_size_variant = json_decode($product->size_variant);
            $db_optional_item = json_decode($product->optional_item);

            if($db_size_variant){
                $size_variant = $db_size_variant;
            }

            if($db_optional_item){
                $optional_item = $db_optional_item;
            }

            return view('admin.variant',compact('size_variant','product','optional_item'));
        }else{
            $notification = trans('admin_validation.Something went wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.product.index')->with($notification);
        }
    }

    public function store_optional_item(Request $request, $id){
        if(count($request->item_names) > 0){
            $optional_item = array();
            foreach($request->item_names as $index => $size){
                if($request->item_names[$index] && $request->item_prices[$index]){
                    $new_item = array(
                        'item' => $request->item_names[$index],
                        'price' => $request->item_prices[$index],
                    );
                    $optional_item[] = $new_item;
                }
            }

            $product = Product::find($id);
            $product->optional_item = json_encode($optional_item);
            $product->save();
        }


        $notification = trans('admin_validation.Inserted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function store(Request $request, $id)
    {
        if(count($request->sizes) > 0){
            $size_variant = array();
            foreach($request->sizes as $index => $size){
                if($request->sizes[$index] && $request->prices[$index]){
                    $new_size = array(
                        'size' => $request->sizes[$index],
                        'price' => $request->prices[$index],
                    );
                    $size_variant[] = $new_size;
                }
            }

            $product = Product::find($id);
            $product->size_variant = json_encode($size_variant);
            $product->save();
        }

        $notification = trans('admin_validation.Inserted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
