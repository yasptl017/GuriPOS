<?php

namespace App\Http\Controllers\WEB\Admin;
use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Image;
use File;

class AboutUsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:admin");
    }

    public function index()
    {
        $about_us = AboutUs::first();

        return view("admin.about-us", compact("about_us"));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "about_short_title" => "required",
            "about_long_title" => "required",
            "about_us" => "required",
            "author_name" => "required",
            "author_comment" => "required",
            "experience_year" => "required",
            "experience_text" => "required",
        ];

        $customMessages = [
            "about_short_title.required" => trans("Short title is required"),
            "about_long_title.required" => trans("Long title is required"),
            "about_us.required" => trans("About us is required"),
            "author_name.required" => trans("Author name is required"),
            "author_comment.required" => trans("Author comment is required"),
            "experience_year.required" => trans("Year is required"),
            "experience_text.required" => trans("Experience text is required"),
        ];

        $this->validate($request, $rules, $customMessages);

        $aboutUs = AboutUs::find($id);

        if ($request->about_us_image) {
            $exist_banner = $aboutUs->about_us_image;
            $extention = $request->about_us_image->getClientOriginalExtension();
            $banner_name = "about-us" .date("-Y-m-d-h-i-s-") .rand(999, 9999) ."." .$extention;
            $banner_name = "uploads/website-images/" . $banner_name;
            Image::make($request->about_us_image)->save(public_path() . "/" . $banner_name);
            $aboutUs->about_us_image = $banner_name;
            $aboutUs->save();

            if ($exist_banner) {
                if (File::exists(public_path() . "/" . $exist_banner)) {
                    unlink(public_path() . "/" . $exist_banner);
                }
            }
        }

        $aboutUs->about_us = $request->about_us;
        $aboutUs->about_us_short_title = $request->about_short_title;
        $aboutUs->about_us_long_title = $request->about_long_title;
        $aboutUs->author_name = $request->author_name;
        $aboutUs->author_comment = $request->author_comment;
        $aboutUs->experience_year = $request->experience_year;
        $aboutUs->experience_text = $request->experience_text;
        $aboutUs->item1_title = $request->item1_title;
        $aboutUs->item1_description = $request->item1_description;
        $aboutUs->item2_title = $request->item2_title;
        $aboutUs->item2_description = $request->item2_description;
        $aboutUs->item3_title = $request->item3_title;
        $aboutUs->item3_description = $request->item3_description;
        $aboutUs->save();

        $notification = trans("admin_validation.Updated Successfully");

        $notification = ["messege" => $notification, "alert-type" => "success"];

        return redirect()
            ->back()
            ->with($notification);
    }

    public function why_choose_us(Request $request, $id)
    {
        $rules = [
            "why_choose_us_short_title" => "required",
            "why_choose_us_long_title" => "required",
            "why_choose_us_description" => "required",
        ];

        $customMessages = [
            "why_choose_us_short_title.required" => trans("Short title is required"),
            "why_choose_us_long_title.required" => trans("Long title is required"),
            "why_choose_us_description.required" => trans("Description is required"),
        ];

        $this->validate($request, $rules, $customMessages);

        $aboutUs = AboutUs::find($id);

        if ($request->why_choose_us_background) {
            $exist_banner = $aboutUs->why_choose_us_background;
            $extention = $request->why_choose_us_background->getClientOriginalExtension();
            $banner_name = "why_choose_us_background" .date("-Y-m-d-h-i-s-") .rand(999, 9999) ."." .$extention;
            $banner_name = "uploads/website-images/" . $banner_name;
            Image::make($request->why_choose_us_background)->save(public_path() . "/" . $banner_name);
            $aboutUs->why_choose_us_background = $banner_name;
            $aboutUs->save();

            if ($exist_banner) {
                if (File::exists(public_path() . "/" . $exist_banner)) {
                    unlink(public_path() . "/" . $exist_banner);
                }
            }
        }


        $aboutUs->why_choose_us_short_title = $request->why_choose_us_short_title;
        $aboutUs->why_choose_us_long_title = $request->why_choose_us_long_title;
        $aboutUs->why_choose_us_description = $request->why_choose_us_description;
        $aboutUs->title_one = $request->title_one;
        $aboutUs->title_two = $request->title_two;
        $aboutUs->title_three = $request->title_three;
        $aboutUs->title_four = $request->title_four;
        $aboutUs->description_one = $request->description_one;
        $aboutUs->description_two = $request->description_two;
        $aboutUs->description_three = $request->description_three;
        $aboutUs->description_four = $request->description_four;
        $aboutUs->save();

        $notification = trans("admin_validation.Updated Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];
        return redirect()->back()->with($notification);
    }

    public function video_update(Request $request, $id)
    {

        $aboutUs = AboutUs::find($id);

        if ($request->vision_bg) {
            $exist_banner = $aboutUs->vision_bg;
            $extention = $request->vision_bg->getClientOriginalExtension();
            $banner_name = "vision_bg" .date("-Y-m-d-h-i-s-") .rand(999, 9999) ."." .$extention;
            $banner_name = "uploads/website-images/" . $banner_name;
            Image::make($request->vision_bg)->save(public_path() . "/" . $banner_name);
            $aboutUs->vision_bg = $banner_name;
            $aboutUs->save();

            if ($exist_banner) {
                if (File::exists(public_path() . "/" . $exist_banner)) {
                    unlink(public_path() . "/" . $exist_banner);
                }
            }
        }

        $aboutUs->vision_title = $request->vision_title;
        $aboutUs->vision_description = $request->vision_description;

        $aboutUs->mission_title = $request->mission_title;
        $aboutUs->mission_description = $request->mission_description;

        $aboutUs->goal_title = $request->goal_title;
        $aboutUs->goal_description = $request->goal_description;
        $aboutUs->save();

        $notification = trans("admin_validation.Updated Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];
        return redirect()->back()->with($notification);
    }


}
