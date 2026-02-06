<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\MaintainanceText;
use App\Models\Product;
use App\Models\BannerImage;
use App\Models\Service;
use App\Models\Coupon;
use App\Models\Blog;
use App\Models\AboutUs;
use App\Models\ContactPage;
use App\Models\ErrorPage;
use App\Models\PopularPost;
use App\Models\BlogCategory;
use App\Models\BreadcrumbImage;
use App\Models\CustomPagination;
use App\Models\Faq;
use App\Models\CustomPage;
use App\Models\TermsAndCondition;
use App\Models\Subscriber;
use App\Mail\SubscriptionVerification;
use App\Mail\ContactMessageInformation;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\ProductReview;
use App\Models\ProductGallery;
use App\Models\Setting;
use App\Models\ContactMessage;
use App\Models\BlogComment;
use App\Models\Testimonial;
use App\Models\GoogleRecaptcha;
use App\Models\Order;
use App\Models\SeoSetting;
use App\Rules\Captcha;
use App\Models\FooterSocialLink;
use App\Models\AnnouncementModal;
use App\Models\MenuVisibility;
use App\Models\GoogleAnalytic;
use App\Models\FacebookPixel;
use App\Models\TawkChat;
use App\Models\CookieConsent;
use App\Models\FooterLink;
use App\Models\Footer;
use App\Models\Homepage;
use App\Models\OurChef;
use App\Models\Counter;
use Mail;
use Str;
use Session;
use Cart;
use Carbon\Carbon;
use Route;

class HomeController extends Controller
{

    public function index()
    {
        $seo_setting = SeoSetting::find(1);

        $homepage = Homepage::first();
        $setting = Setting::first();

        $sliders = Slider::all();
        $slider_background = $setting->slider_background;
        $foreground_image_one = $setting->slider_foreground_one;
        $foreground_image_two = $setting->slider_foreground_two;

        $slider = (object) array(
            'slider_background' => $slider_background,
            'foreground_image_one' => $foreground_image_one,
            'foreground_image_two' => $foreground_image_two,
            'sliders' => $sliders
        );

        $services = Service::where('status', 1)->get();
        $service = (object) array(
            'status' => $homepage->service_status == 1 ? true : false,
            'services' => $services
        );

        $today_special_products = Product::where(['status' => 1, 'today_special' => 1])->get()->take($homepage->today_special_item);
        $today_special_product = (object) array(
            'status' => $homepage->today_special_status == 1 ? true : false,
            'image' => $homepage->today_special_image,
            'short_title' => $homepage->today_special_short_title,
            'long_title' => $homepage->today_special_long_title,
            'description' => $homepage->today_special_description,
            'products' => $today_special_products
        );
        $categories = Category::where('status', 1)->where('show_homepage',1)->get();
        $custom_product_ids = array();
        foreach($categories as $category){
            $products = Product::where(['status' => 1, 'category_id' => $category->id])->select('id','status','category_id')->get();
            foreach($products as $product){
                $custom_product_ids[] = $product->id;
            }
        }
$products = Product::with('category')->where(['status' => 1])->whereIn('id', $custom_product_ids)->get();
        
        $menu_section = (object) array(
            'status' => $homepage->menu_status == 1 ? true : false,
            'short_title' => $homepage->menu_short_title,
            'long_title' => $homepage->menu_long_title,
            'description' => $homepage->menu_description,
            'left_image' => $homepage->menu_left_image,
            'right_image' => $homepage->menu_right_image,
            'categories' => $categories,
            'products' => $products
        );

        $ad_banners = BannerImage::whereIn('id', [1,2])->get();
        $advertisement = (object) array(
            'status' => $homepage->advertisement_status == 1 ? true : false,
            'banners' => $ad_banners,
        );

        $chefs = OurChef::where('status', 1)->get()->take($homepage->chef_item);
        $our_chef = (object) array(
            'status' => $homepage->chef_status == 1 ? true : false,
            'short_title' => $homepage->chef_short_title,
            'long_title' => $homepage->chef_long_title,
            'description' => $homepage->chef_description,
            'left_image' => $homepage->chef_left_image,
            'right_image' => $homepage->chef_right_image,
            'chefs' => $chefs
        );

        $app_section = (object) array(
            'status' => $homepage->mobile_app_status == 1 ? true : false,
            'title' => $setting->app_title,
            'description' => $setting->app_description,
            'play_store_link' => $setting->play_store_link,
            'app_store_link' => $setting->app_store_link,
            'image' => $setting->app_image,
            'home1_background' => $setting->app_background_one,
            'home2_background' => $setting->app_background_two,
        );

        $counters = Counter::all();
        $counter = (object) array(
            'status' => $homepage->counter_status == 1 ? true : false,
            'background_image' => $setting->counter_background,
            'counters' => $counters
        );

        $testimonials = Testimonial::where('status', 1)->get()->take($homepage->testimonial_item);
        $testimonial = (object) array(
            'status' => $homepage->testimonial_status == 1 ? true : false,
            'short_title' => $homepage->testimonial_short_title,
            'long_title' => $homepage->testimonial_long_title,
            'description' => $homepage->testimonial_description,
            'testimonials' => $testimonials
        );

        $blogs = Blog::with('category')->where(['status' => 1, 'show_homepage' => 1])->get()->take($homepage->blog_item);
        $blog = (object) array(
            'status' => $homepage->blog_status == 1 ? true : false,
            'short_title' => $homepage->blog_short_title,
            'long_title' => $homepage->blog_long_title,
            'description' => $homepage->blog_description,
            'home1_background' => $homepage->blog_background,
            'home2_background' => $homepage->blog_background_2,
            'blogs' => $blogs
        );

        $about_us = AboutUs::first();
        $video_section = (object) array(
            'status' => $homepage->video_section_status == 1 ? true : false,
            'title' => $about_us->video_title,
            'video_id' => $about_us->video_id,
            'background_image' => $about_us->video_background,
        );

        $items = array(
            (object) array(
                'title' => $about_us->title_one,
                'icon' => $about_us->icon_one,
            ),
            (object) array(
                'title' => $about_us->title_two,
                'icon' => $about_us->icon_two,
            ),
            (object) array(
                'title' => $about_us->title_three,
                'icon' => $about_us->icon_three,
            ),
            (object) array(
                'title' => $about_us->title_four,
                'icon' => $about_us->icon_four,
            )
        );
        $why_choose_us = (object) array(
            'status' => $homepage->why_choose_us_status == 1 ? true : false,
            'short_title' => $about_us->why_choose_us_short_title,
            'long_title' => $about_us->why_choose_us_long_title,
            'description' => $about_us->why_choose_us_description,
            'background_image' => $about_us->why_choose_us_background,
            'foreground_image_one' => $about_us->why_choose_us_foreground_one,
            'foreground_image_two' => $about_us->why_choose_us_foreground_two,
            'items' => $items
        );

        $cart_product_ids = collect(Cart::content())->pluck('id')->unique()->values()->all();

        return view('index')->with([
            'seo_setting' => $seo_setting,
            'slider' => $slider,
            'service' => $service,
            'today_special_product' => $today_special_product,
            'menu_section' => $menu_section,
            'advertisement' => $advertisement,
            'our_chef' => $our_chef,
            'app_section' => $app_section,
            'counter' => $counter,
            'testimonial' => $testimonial,
            'blog' => $blog,
            'video_section' => $video_section,
            'why_choose_us' => $why_choose_us,
            'cart_product_ids' => $cart_product_ids
        ]);

    }

    public function about_us(){
        $homepage = Homepage::first();
        $about_us = AboutUs::first();
        $setting = Setting::first();

        $about = (object) array(
            'short_title' => $about_us->about_us_short_title,
            'long_title' => $about_us->about_us_long_title,
            'image' => $about_us->about_us_image,
            'about_us' => $about_us->about_us,
        );

        $video_section = (object) array(
            'title' => $about_us->video_title,
            'video_id' => $about_us->video_id,
            'background_image' => $about_us->video_background,
        );

        $items = array(
            (object) array(
                'title' => $about_us->title_one,
                'icon' => $about_us->icon_one,
            ),
            (object) array(
                'title' => $about_us->title_two,
                'icon' => $about_us->icon_two,
            ),
            (object) array(
                'title' => $about_us->title_three,
                'icon' => $about_us->icon_three,
            ),
            (object) array(
                'title' => $about_us->title_four,
                'icon' => $about_us->icon_four,
            )
        );

        $why_choose_us = (object) array(
            'short_title' => $about_us->why_choose_us_short_title,
            'long_title' => $about_us->why_choose_us_long_title,
            'description' => $about_us->why_choose_us_description,
            'background_image' => $about_us->why_choose_us_background,
            'foreground_image_one' => $about_us->why_choose_us_foreground_one,
            'foreground_image_two' => $about_us->why_choose_us_foreground_two,
            'items' => $items
        );

        $counters = Counter::all();
        $counter = (object) array(
            'background_image' => $setting->counter_background,
            'counters' => $counters
        );

        $testimonials = Testimonial::where('status', 1)->get()->take($homepage->testimonial_item);
        $testimonial = (object) array(
            'short_title' => $homepage->testimonial_short_title,
            'long_title' => $homepage->testimonial_long_title,
            'description' => $homepage->testimonial_description,
            'testimonials' => $testimonials
        );

        $seo_setting = SeoSetting::find(2);

        return view('about_us')->with([
            'seo_setting' => $seo_setting,
            'about_us' => $about_us,
            'video_section' => $video_section,
            'why_choose_us' => $why_choose_us,
            'counter' => $counter,
            'testimonial' => $testimonial,
        ]);
    }

    public function reserve_table(){
        return view('reserve_table');
    }
    
    public function social(){
        return view('social');
    }
    
        public function location(){
        return view('location');
    }

    public function offers(){
        $coupons = Coupon::where('status', 1)
                        ->orderBy('id', 'desc')
                        ->get();
        $setting = Setting::first();
        return view('offers', compact('coupons', 'setting'));
    }


    public function contact_us(){
        $contact = ContactPage::first();
        $recaptcha_setting = GoogleRecaptcha::first();

        $seo_setting = SeoSetting::find(3);

        return view('contact_us')->with([
            'seo_setting' => $seo_setting,
            'contact' => $contact,
            'recaptcha_setting' => $recaptcha_setting,
        ]);
    }

    public function send_contact_message(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'message'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'subject.required' => trans('user_validation.Subject is required'),
            'message.required' => trans('user_validation.Message is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $setting = Setting::first();
        if($setting->enable_save_contact_message == 1){
            $contact = new ContactMessage();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->subject = $request->subject;
            $contact->phone = $request->phone;
            $contact->message = $request->message;
            $contact->save();
        }

        MailHelper::setMailConfig();
        $template = EmailTemplate::where('id',2)->first();
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{name}}',$request->name,$message);
        $message = str_replace('{{email}}',$request->email,$message);
        $message = str_replace('{{phone}}',$request->phone,$message);
        $message = str_replace('{{subject}}',$request->subject,$message);
        $message = str_replace('{{message}}',$request->message,$message);
        Mail::to($setting->contact_email)->send(new ContactMessageInformation($message,$subject));

        $notification = trans('user_validation.Message send successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function blogs(Request $request){
        $paginateQty = CustomPagination::whereId('1')->first()->qty;
        $blogs = Blog::orderBy('id','desc')->where(['status' => 1]);

        if($request->search){
            $blogs = $blogs->where('title','LIKE','%'.$request->search.'%');
        }

        if($request->category){
            $category = BlogCategory::where('slug',$request->category)->first();
            $blogs = $blogs->where('blog_category_id', $category->id);
        }

        $blogs = $blogs->paginate($paginateQty);

        $seo_setting = SeoSetting::find(6);

        return view('blog')->with(['blogs' => $blogs, 'seo_setting' => $seo_setting]);
    }

    public function show_blog($slug){
        $blog = Blog::with('category')->where(['status' => 1, 'slug'=>$slug])->first();
        if(!$blog){
            abort(404);
        }
        $popular_posts = PopularPost::with('blog')->where(['status' => 1])->get();

        $blog_arr = array();
        foreach($popular_posts as $popular_post){
            $blog_arr[] = $popular_post->blog_id;
        }

        $popular_posts = Blog::orderBy('id','desc')->where(['status' => 1])->whereIn('id', $blog_arr)->get();

        $categories = BlogCategory::where(['status' => 1])->get();
        $recaptcha_setting = GoogleRecaptcha::first();
        $active_comments = BlogComment::where('blog_id', $blog->id)->orderBy('id','desc')->get();

        $next_blog = Blog::where('id', '>', $blog->id)->orderBy('id','asc')->first();
        $prev_blog = Blog::where('id', '<', $blog->id)->orderBy('id','desc')->first();

        return view('blog_detail')->with(['blog' => $blog, 'popular_posts' => $popular_posts, 'categories' => $categories, 'recaptcha_setting' => $recaptcha_setting, 'active_comments' => $active_comments, 'next_blog' => $next_blog, 'prev_blog' => $prev_blog]);

    }

    public function blog_comment(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'comment'=>'required',
            'blog_id'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'comment.required' => trans('user_validation.Comment is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $comment = new BlogComment();
        $comment->blog_id = $request->blog_id;
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->save();
        $notification = trans('user_validation.Blog comment submited successfully');
        return response()->json(['status' => 1,'message' => $notification],200);
    }


    public function faq(){
        $faqs = FAQ::orderBy('id','desc')->where('status',1)->get();
        $recaptcha_setting = GoogleRecaptcha::first();

        return view('faq')->with(['faqs' => $faqs, 'recaptcha_setting' => $recaptcha_setting]);
    }

    public function custom_page($slug){
        $page = CustomPage::where(['slug' => $slug, 'status' => 1])->first();
        return view('custom_page')->with(['page'=> $page]);
    }

    public function terms_and_condition(){
        $terms_conditions = TermsAndCondition::select('terms_and_condition')->first();
        return view('terms_and_conditions')->with(['terms_conditions'=> $terms_conditions]);
    }

    public function privacy_policy(){
        $privacy_policy = TermsAndCondition::select('privacy_policy')->first();
        return view('privacy_policy')->with(['privacy_policy'=> $privacy_policy]);
    }

    public function products(Request $request){
        $categories = Category::where(['status' => 1])->get();
        $paginate_qty = CustomPagination::whereId('2')->first()->qty;
        $products = Product::with('category')->orderBy('id','desc')->where(['status' => 1]);

        if($request->category) {
            $category = Category::where('slug',$request->category)->first();
            $products = $products->where('category_id', $category->id);
        }

        if($request->search) {
            $products = $products->where('name','LIKE','%'.$request->search.'%');
        }

        $products = $products->paginate($paginate_qty);
        $products = $products->appends($request->all());

        $seo_setting = SeoSetting::find(9);

        return view('product')->with([
            'seo_setting' => $seo_setting,
            'categories' => $categories,
            'products' => $products,
        ]);

    }

    public function show_product($slug){
        $product = Product::with('category')->where(['status' => 1, 'slug' => $slug])->first();
        if(!$product){
            abort(404);
        }

        $review_paginate_qty = CustomPagination::whereId('5')->first()->qty;

        $product_reviews = ProductReview::with('user')->where(['status' => 1, 'product_id' =>$product->id])->paginate($review_paginate_qty);
        $gellery = ProductGallery::where('product_id', $product->id)->get();

        if($product->size_variant != null){
            $size_variants = json_decode($product->size_variant);
        }else{
            $size_variants = array();
        }

        if($product->optional_item != null){
            $optional_items = json_decode($product->optional_item);
        }else{
            $optional_items = array();
        }

        $related_products = Product::with('category')->where(['category_id' => $product->category_id, 'status' => 1])->where('id' , '!=', $product->id)->get()->take(10);

        $recaptcha_setting = GoogleRecaptcha::first();
        $setting = Setting::first();
        $default_profile = $setting->default_avatar;

        return view('product_detail')->with([
            'product' => $product,
            'size_variants' => $size_variants,
            'optional_items' => $optional_items,
            'gellery' => $gellery,
            'product_reviews' => $product_reviews,
            'related_products' => $related_products,
            'recaptcha_setting' => $recaptcha_setting,
            'default_profile' => $default_profile,
            'review_paginate_qty' => $review_paginate_qty
        ]);
    }

    public function load_product_model($product_id){
        $product = Product::with('category')->where(['status' => 1, 'id' => $product_id])->first();
        if(!$product){
            $notification = trans('user_validation.Something went wrong');
            return response()->json(['message' => $notification],403);
        }

        if($product->size_variant != null){
            $size_variants = json_decode($product->size_variant);
        }else{
            $size_variants = array();
        }

        if($product->optional_item != null){
            $optional_items = json_decode($product->optional_item);
        }else{
            $optional_items = array();
        }

        return view('product_popup_view')->with([
            'product' => $product,
            'size_variants' => $size_variants,
            'optional_items' => $optional_items,
        ]);
    }

    public function productReviewList($id){
        $reviews = ProductReview::with('user')->where(['product_id' => $id, 'status' => 1])->paginate(10);
        return response()->json(['reviews' => $reviews]);
    }

    public function subscribeRequest(Request $request){
        if($request->email != null){
            $isExist = Subscriber::where('email', $request->email)->count();
            if($isExist == 0){
                $subscriber = new Subscriber();
                $subscriber->email = $request->email;
                $subscriber->verified_token = random_int(100000, 999999);
                $subscriber->save();
                MailHelper::setMailConfig();
                $template=EmailTemplate::where('id',3)->first();
                $message=$template->description;
                $subject=$template->subject;
                Mail::to($subscriber->email)->send(new SubscriptionVerification($subscriber,$message,$subject));

                return response()->json(['message' => trans('user_validation.Subscription successfully, please verified your email')]);

            }else{
                return response()->json(['message' => trans('user_validation.Email already exist')],403);

            }
        }else{
            return response()->json(['message' => trans('user_validation.Email Field is required')],403);
        }
    }

    public function subscriberVerifcation(Request $request, $token){
        $subscriber = Subscriber::where(['verified_token' => $token])->first();
        if($subscriber){
            $subscriber->verified_token = null;
            $subscriber->is_verified = 1;
            $subscriber->save();

            $notification=  trans('user_validation.Verification successful');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('home')->with($notification);

        }else{
            $notification=  trans('user_validation.Invalid token');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('home')->with($notification);
        }
    }

    public function our_chef(){
        $our_chefs = OurChef::where('status', 1)->orderBy('id','desc')->get();

        return view('our_chef')->with(['our_chefs' => $our_chefs]);
    }

    public function testimonial(){
        $testimonials = Testimonial::where('status', 1)->orderBy('id','desc')->get();

        return view('testimonial')->with(['testimonials' => $testimonials]);
    }























































































}



