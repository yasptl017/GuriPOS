<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\DeliveryArea;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AddressCotroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $user = Auth::guard('web')->user();

        $personal_info = User::select('id', 'name', 'phone', 'email', 'image', 'address')->find($user->id);

        $addresses = Address::with('deliveryArea')->where(['user_id' => $user->id])->get();


        return view('user.address')->with([
            'personal_info' => $personal_info,
            'addresses' => $addresses,
        ]);
    }

    public function create()
    {
        $user = Auth::guard('web')->user();

        $personal_info = User::select('id', 'name', 'phone', 'email', 'image', 'address')->find($user->id);
        $delivery_areas = DeliveryArea::where('status', 1)->get();

        return view('user.address_create')->with([
            'personal_info' => $personal_info,
            'delivery_areas' => $delivery_areas,
        ]);
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('Full name is required'),
            'phone.required' => trans('Phone number is required'),
            'email.required' => trans('Email is required'),
            'address.required' => trans('Address is required'),
        ];
    
        $this->validate($request, $rules, $customMessages);
    
        $user = Auth::guard('web')->user();
        $is_exist = Address::where(['user_id' => $user->id])->count();
        $address = new Address();
        $address->user_id = $user->id;
        $address->first_name = $request->name;
        $address->email = $request->email;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->latitude = $request->distance; // Assuming distance is stored in meters
    
        // Convert distance to kilometers
        $distanceInKm = $request->distance / 1000;
    
        // Find the matching delivery area
        $deliveryArea = DeliveryArea::where('min_range', '<=', $distanceInKm)
                                    ->where('max_range', '>=', $distanceInKm)
                                    ->first();
    
        if ($deliveryArea) {
            $address->delivery_area_id = $deliveryArea->id;
        } else {
            // Handle the case where no delivery area matches
            return redirect()->back()->withErrors(['distance' => 'No delivery area matches the provided distance.']);
        }
    
        $address->type = 'home';
        if ($is_exist == 0) {
            $address->default_address = 'Yes';
        }
        $address->save();
    
        $notification = trans('user_validation.Create Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('address.index')->with($notification);
    }
    

    public function edit($id)
    {
        $user = Auth::guard('web')->user();

        $personal_info = User::select('id', 'name', 'phone', 'email', 'image', 'address')->find($user->id);
        $delivery_areas = DeliveryArea::where('status', 1)->get();
        $address = Address::where(['id' => $id])->first();

        return view('user.address_edit')->with([
            'personal_info' => $personal_info,
            'delivery_areas' => $delivery_areas,
            'address' => $address,
        ]);
    }

    public function update(Request $request, $id)
    {

        $user = Auth::guard('web')->user();
        $address = Address::where(['user_id' => $user->id, 'id' => $id])->first();
        if (!$address) {
            $notification = trans('user_validation.Something went wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('address.index')->with($notification);
        }

        $rules = [
            'delivery_area_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'address_type' => 'required',
        ];
        $customMessages = [
            'delivery_area_id.required' => trans('user_validation.Delivery area is required'),
            'first_name.required' => trans('user_validation.First name is required'),
            'last_name.required' => trans('user_validation.Last name is required'),
            'address.required' => trans('user_validation.Address is required'),
            'address_type.required' => trans('user_validation.Address type is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $address->delivery_area_id = $request->delivery_area_id;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->email = $request->email;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->type = $request->address_type;
        $address->save();

        $notification = trans('user_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('address.index')->with($notification);
    }

    public function destroy($id)
    {
        $user = Auth::guard('web')->user();
        $address = Address::where(['id' => $id])->first();

        if ($address->default_address == 'Yes') {
            $notification = trans('user_validation.Opps!! Default address can not be delete.');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->back()->with($notification);
        } else {
            $address->delete();
            $notification = trans('user_validation.Delete Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->back()->with($notification);
        }
    }


    public function store_address_from_checkout(Request $request)
    {
        $rules = [
            'distance' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'address_type' => 'required',
        ];
        $customMessages = [
            'distance.required' => trans('user_validation.Distance is required'),
            'first_name.required' => trans('user_validation.First name is required'),
            'last_name.required' => trans('user_validation.Last name is required'),
            'address.required' => trans('user_validation.Address is required'),
            'address_type.required' => trans('user_validation.Address type is required'),
        ];
        $this->validate($request, $rules, $customMessages);
    
        $user = Auth::guard('web')->user();
        $is_exist = Address::where(['user_id' => $user->id])->count();
        $address = new Address();
        $address->user_id = $user->id;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->email = $request->email;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->type = $request->address_type;
    
        // Convert distance to kilometers
        $distanceInKm = $request->distance / 1000;
    
        // Find the matching delivery area
        $deliveryArea = DeliveryArea::where('min_range', '<=', $distanceInKm)
                                    ->where('max_range', '>=', $distanceInKm)
                                    ->first();
    
        if ($deliveryArea) {
            $address->delivery_area_id = $deliveryArea->id;
        } else {
            // Handle the case where no delivery area matches
            return redirect()->back()->withErrors(['distance' => 'No delivery area matches the provided distance.']);
        }
        if ($is_exist == 0) {
            $address->default_address = 'Yes';
        }
        $address->save();
        $notification = trans('user_validation.Create Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}

