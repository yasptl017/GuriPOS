<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DefaultsController;
use App\Http\Controllers\DeliveryCheckoutController;
use App\Http\Controllers\GuestPaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WEB\Admin\AboutUsController;
use App\Http\Controllers\WEB\Admin\AdminController;
use App\Http\Controllers\WEB\Admin\AdminProfileController;
use App\Http\Controllers\WEB\Admin\Auth\AdminForgotPasswordController;
use App\Http\Controllers\WEB\Admin\Auth\AdminLoginController;
use App\Http\Controllers\WEB\Admin\BreadcrumbController;
use App\Http\Controllers\WEB\Admin\ContactMessageController;
use App\Http\Controllers\WEB\Admin\ContactPageController;
use App\Http\Controllers\WEB\Admin\ContentController;
use App\Http\Controllers\WEB\Admin\CounterController;
use App\Http\Controllers\WEB\Admin\CouponController;
use App\Http\Controllers\WEB\Admin\CustomerController;
use App\Http\Controllers\WEB\Admin\CustomPageController;
use App\Http\Controllers\WEB\Admin\DashboardController;
use App\Http\Controllers\WEB\Admin\DeliveryAreaCotroller;
use App\Http\Controllers\WEB\Admin\EmailConfigurationController;
use App\Http\Controllers\WEB\Admin\EmailTemplateController;
use App\Http\Controllers\WEB\Admin\ErrorPageController;
use App\Http\Controllers\WEB\Admin\FaqController;
use App\Http\Controllers\WEB\Admin\FooterController;
use App\Http\Controllers\WEB\Admin\FooterSocialLinkController;
use App\Http\Controllers\WEB\Admin\HomepageController;
use App\Http\Controllers\WEB\Admin\LanguageController;
use App\Http\Controllers\WEB\Admin\MenuVisibilityController;
use App\Http\Controllers\WEB\Admin\OrderController;
use App\Http\Controllers\WEB\Admin\OurChefController;
use App\Http\Controllers\WEB\Admin\PaymentMethodController;
use App\Http\Controllers\WEB\Admin\PrivacyPolicyController;
use App\Http\Controllers\WEB\Admin\ProductCategoryController;
use App\Http\Controllers\WEB\Admin\ProductController;
use App\Http\Controllers\WEB\Admin\ProductGalleryController;
use App\Http\Controllers\WEB\Admin\ProductVariantController;
use App\Http\Controllers\WEB\Admin\ServiceController;
use App\Http\Controllers\WEB\Admin\SettingController;
use App\Http\Controllers\WEB\Admin\SliderController;
use App\Http\Controllers\WEB\Admin\SubscriberController;
use App\Http\Controllers\WEB\Admin\TermsAndConditionController;
use App\Http\Controllers\WEB\Admin\TestimonialController;
use App\Http\Controllers\WEB\Admin\WorkingHoursController;
use App\Http\Controllers\WEB\Admin\OrderControlController;
use App\Http\Controllers\WEB\User\AddressCotroller;
use App\Http\Controllers\WEB\User\PaymentController;
use App\Http\Controllers\WEB\User\PaypalController;
use App\Http\Controllers\WEB\User\UserProfileController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


//Auth::guard('admin')->onceUsingId(1);
//Auth::onceUsingId(1);

//Log::error('hello there', User::query()->get()->toArray());

Route::group(['middleware' => ['demo', 'XSS']], function () {
    Route::group(['middleware' => ['maintainance', 'HtmlSpecialchars']], function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/reserve-table', [HomeController::class, 'reserve_table'])->name('reserve-table');
        Route::get('/offers', [HomeController::class, 'offers'])->name('offers');
        Route::get('/social', [HomeController::class, 'social'])->name('social');
        Route::get('/location', [HomeController::class, 'location'])->name('location');
        Route::get('/about-us', [HomeController::class, 'about_us'])->name('about-us');
        Route::get('/about-us', [HomeController::class, 'about_us'])->name('about-us');
        Route::get('/contact-us', [HomeController::class, 'contact_us'])->name('contact-us');
        Route::post('/send-contact-us', [HomeController::class, 'send_contact_message'])->name('send-contact-us');
        Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs');
        Route::get('/blog/{slug}', [HomeController::class, 'show_blog'])->name('show-blog');
        Route::post('/blog-comment', [HomeController::class, 'blog_comment'])->name('blog-comment');
        Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
        Route::get('/page/{slug}', [HomeController::class, 'custom_page'])->name('show-page');
        Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
        Route::get('/terms-and-condition', [HomeController::class, 'terms_and_condition'])->name('terms-and-condition');
        Route::get('/privacy-policy', [HomeController::class, 'privacy_policy'])->name('privacy-policy');
        Route::get('/products', [HomeController::class, 'products'])->name('products');
        Route::get('/product/{slug}', [HomeController::class, 'show_product'])->name('show-product');
        Route::get('/load-product-modal/{id}', [HomeController::class, 'load_product_model'])->name('load-product-modal');

        Route::get('/our-chef', [HomeController::class, 'our_chef'])->name('our-chef');
        Route::get('/testimonial', [HomeController::class, 'testimonial'])->name('testimonial');

        Route::post('/subscribe-request', [HomeController::class, 'subscribeRequest'])->name('subscribe-request');
        Route::get('/subscriber-verification/{token}', [HomeController::class, 'subscriberVerifcation'])->name('subscriber-verification');


        Route::get('/login-auth', [LoginController::class, 'login_auth_page'])->name('login-auth');
        Route::get('/login', [LoginController::class, 'login_page'])->name('login');
        Route::post('/store-login', [LoginController::class, 'store_page'])->name('store-login');
        Route::get('/user-logout', [LoginController::class, 'user_logout'])->name('user-logout');

        Route::get('/forget-password', [LoginController::class, 'forget_page'])->name('forget-password');
        Route::post('/send-forget-password', [LoginController::class, 'send_reset_link'])->name('send-forget-password');
        Route::get('/reset-password/{id}', [LoginController::class, 'reset_password'])->name('reset-password');
        Route::post('/store-reset-password/{id}', [LoginController::class, 'store_reset_password'])->name('store-reset-password');
        Route::post('update-delivery-charge', [PaymentController::class, 'updateDeliveryCharge'])->name('update-delivery-charge');
        Route::get('/register', [RegisterController::class, 'register_page'])->name('register');
        Route::get('eway-payment', [PaymentController::class, 'eway_payment'])->name('eway-payment');
        Route::get('/eway-success', [PaymentController::class, 'eway_success'])->name('eway-success');
        Route::get('/eway-failed', [PaymentController::class, 'eway_failed'])->name('eway-failed');
        Route::put('update-eway', [PaymentMethodController::class, 'updateEway'])->name('update-eway');

        //new changes
        Route::get('/auth', [RegisterController::class, 'auth_page'])->name('auth');
        Route::get('/loading', [RegisterController::class, 'loading_page'])->name('loading');
        Route::post('/verifyRegister', [RegisterController::class, 'verifyRegister'])->name('verifyRegister');

        Route::post('/store-register', [RegisterController::class, 'store_register'])->name('store-register');
        Route::get('/verify-register/{token}', [RegisterController::class, 'verify_register'])->name('verify-register');

        Route::get('/dashboard', [UserProfileController::class, 'dashboard'])->name('dashboard');
        Route::get('/edit-profile', [UserProfileController::class, 'edit_profile'])->name('edit-profile');
        Route::post('/update-profile', [UserProfileController::class, 'update_profile'])->name('update-profile');
        Route::get('/change-password', [UserProfileController::class, 'change_password'])->name('change-password');
        Route::post('/update-password', [UserProfileController::class, 'update_password'])->name('update-password');
        Route::post('/upload-user-avatar', [UserProfileController::class, 'upload_user_avatar'])->name('upload-user-avatar');
        Route::get('/review-list', [UserProfileController::class, 'review_list'])->name('review-list');
        Route::get('/wishlists', [UserProfileController::class, 'wishlists'])->name('wishlists');
        Route::get('/add-to-wishlist/{id}', [UserProfileController::class, 'add_to_wishlist'])->name('add-to-wishlist');
        Route::delete('/remove-to-wishlist/{id}', [UserProfileController::class, 'remove_to_wishlist'])->name('remove-to-wishlist');
        Route::post('/submit-review', [UserProfileController::class, 'store_review'])->name('submit-review');

        Route::get('/orders', [UserProfileController::class, 'orders'])->name('orders');
        Route::get('/single-order/{order_id}', [UserProfileController::class, 'single_order'])->name('single-order');

        Route::get('/reservation', [UserProfileController::class, 'reservation'])->name('reservation');
        Route::post('/store-reservation', [UserProfileController::class, 'store_reservation'])->name('store-reservation');

        Route::resource('address', AddressCotroller::class);
        Route::post('store-address-from-checkout', [AddressCotroller::class, ''])->name('store-address-from-checkout');

        Route::get('cart', [CartController::class, 'cart'])->name('cart');
        Route::get('add-to-cart', [CartController::class, 'add_to_cart'])->name('add-to-cart');
        Route::get('remove-cart-item/{rowId}', [CartController::class, 'remove_cart_item'])->name('remove-cart-item');
        Route::get('cart-clear', [CartController::class, 'cart_clear'])->name('cart-clear');
        Route::get('cart-quantity-update', [CartController::class, 'cart_quantity_update'])->name('cart-quantity-update');

        Route::get('load-cart-item', [CartController::class, 'load_cart_item'])->name('load-cart-item');
        Route::get('apply-coupon', [CartController::class, 'apply_coupon'])->name('apply-coupon');
        Route::get('apply-coupon-from-checkout', [CartController::class, 'apply_coupon_from_checkout'])->name('apply-coupon-from-checkout');


        Route::get('loc', [PaymentController::class, 'loc'])->name('loc');

        Route::get('payment', [PaymentController::class, 'payment'])->name('payment');
        Route::get('picpayment', [PaymentController::class, 'picpayment'])->name('picpayment');

        Route::get('handcash-payment', [PaymentController::class, 'handcash_payment'])->name('handcash-payment');
        Route::post('bank-payment', [PaymentController::class, 'bank_payment'])->name('bank-payment');

        Route::post('pay-with-razorpay', [PaymentController::class, 'razorpay_payment'])->name('pay-with-razorpay');
        Route::post('pay-with-flutterwave', [PaymentController::class, 'razorpay_flutterwave'])->name('pay-with-flutterwave');

        Route::get('/pay-with-mollie', [PaymentController::class, 'pay_with_mollie'])->name('pay-with-mollie');
        Route::get('/mollie-payment-success', [PaymentController::class, 'mollie_payment_success'])->name('mollie-payment-success');
        Route::post('/pay-with-paystack', [PaymentController::class, 'pay_with_paystack'])->name('pay-with-paystack');

        Route::get('/pay-with-instamojo', [PaymentController::class, 'pay_with_instamojo'])->name('pay-with-instamojo');
        Route::get('/instamojo-response', [PaymentController::class, 'instamojo_response'])->name('instamojo-response');

        Route::get('/sslcommerz-pay', [PaymentController::class, 'sslcommerz'])->name('sslcommerz-pay');
        Route::post('/sslcommerz-success', [PaymentController::class, 'sslcommerz_success'])->name('sslcommerz-success');
        Route::post('/sslcommerz-failed', [PaymentController::class, 'sslcommerz_failed'])->name('sslcommerz-failed');

        Route::get('/pay-with-paypal', [PaypalController::class, 'payWithPaypal'])->name('pay-with-paypal');
        Route::get('/paypal-payment-success', [PaypalController::class, 'paypalPaymentSuccess'])->name('paypal-payment-success');
        Route::get('/paypal-payment-cancled', [PaypalController::class, 'paypalPaymentCancled'])->name('paypal-payment-cancled');


    });


// start admin routes
    Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
        // start auth route

        Route::put('update-defaults', [DefaultsController::class, 'updateDefaults'])->name('update-defaults');
        Route::get('login', [AdminLoginController::class, 'adminLoginPage'])->name('login');
        Route::post('login', [AdminLoginController::class, 'storeLogin'])->name('login');
        Route::post('logout', [AdminLoginController::class, 'adminLogout'])->name('logout');
        Route::get('forget-password', [AdminForgotPasswordController::class, 'forgetPassword'])->name('forget-password');
        Route::post('send-forget-password', [AdminForgotPasswordController::class, 'sendForgetEmail'])->name('send.forget.password');
        Route::get('reset-password/{token}', [AdminForgotPasswordController::class, 'resetPassword'])->name('reset.password');
        Route::post('password-store/{token}', [AdminForgotPasswordController::class, 'storeResetData'])->name('store.reset.password');
        // end auth route

        Route::put('update-eway', [PaymentMethodController::class, 'updateEway'])->name('update-eway');
        Route::get('/', [DashboardController::class, 'dashobard'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'dashobard'])->name('dashboard');
        Route::get('profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::put('profile-update', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('password-update', [AdminProfileController::class, 'password_update'])->name('password.update');

        Route::resource('product-category', ProductCategoryController::class);
        Route::put('product-category-status/{id}', [ProductCategoryController::class, 'changeStatus'])->name('product.category.status');

        Route::resource('testimonial', TestimonialController::class);
        Route::put('testimonial-status/{id}', [TestimonialController::class, 'changeStatus'])->name('testimonial.status');

        Route::resource('our-chef', OurChefController::class);
        Route::put('our-chef-status/{id}', [OurChefController::class, 'changeStatus'])->name('our-chef.status');

        Route::resource('product', ProductController::class);
        Route::get('create-product-info', [ProductController::class, 'create'])->name('create-product-info');
        Route::put('product-status/{id}', [ProductController::class, 'changeStatus'])->name('product.status');

        Route::get('product-variant/{id}', [ProductVariantController::class, 'index'])->name('product-variant');
        Route::get('create-product-variant/{id}', [ProductVariantController::class, 'create'])->name('create-product-variant');
        Route::post('store-product-variant/{id}', [ProductVariantController::class, 'store'])->name('store-product-variant');
        Route::post('store-optional-item/{id}', [ProductVariantController::class, 'store_optional_item'])->name('store-optional-item');

        Route::get('product-gallery/{id}', [ProductGalleryController::class, 'index'])->name('product-gallery');
        Route::post('store-product-gallery', [ProductGalleryController::class, 'store'])->name('store-product-gallery');
        Route::delete('delete-product-image/{id}', [ProductGalleryController::class, 'destroy'])->name('delete-product-image');

        Route::resource('service', ServiceController::class);
        Route::put('service-status/{id}', [ServiceController::class, 'changeStatus'])->name('service.status');

        Route::resource('about-us', AboutUsController::class);
        Route::put('why-choose-us.update/{id}', [AboutUsController::class, 'why_choose_us'])->name('why-choose-us.update');
        Route::put('video-update/{id}', [AboutUsController::class, 'video_update'])->name('video-update');

        Route::resource('contact-us', ContactPageController::class);

        Route::resource('custom-page', CustomPageController::class);
        Route::put('custom-page-status/{id}', [CustomPageController::class, 'changeStatus'])->name('custom-page.status');

        Route::resource('terms-and-condition', TermsAndConditionController::class);
        Route::resource('privacy-policy', PrivacyPolicyController::class);


        Route::get('subscriber', [SubscriberController::class, 'index'])->name('subscriber');
        Route::delete('delete-subscriber/{id}', [SubscriberController::class, 'destroy'])->name('delete-subscriber');
        Route::post('specification-subscriber-email/{id}', [SubscriberController::class, 'specificationSubscriberEmail'])->name('specification-subscriber-email');
        Route::post('each-subscriber-email', [SubscriberController::class, 'eachSubscriberEmail'])->name('each-subscriber-email');

        Route::get('review', [ContactMessageController::class, 'review'])->name('review');

        Route::get('contact-message', [ContactMessageController::class, 'index'])->name('contact-message');
        Route::get('show-contact-message/{id}', [ContactMessageController::class, 'show'])->name('show-contact-message');
        Route::delete('delete-contact-message/{id}', [ContactMessageController::class, 'destroy'])->name('delete-contact-message');
        Route::put('enable-save-contact-message', [ContactMessageController::class, 'handleSaveContactMessage'])->name('enable-save-contact-message');

        Route::get('email-configuration', [EmailConfigurationController::class, 'index'])->name('email-configuration');
        Route::put('update-email-configuraion', [EmailConfigurationController::class, 'update'])->name('update-email-configuraion');

        Route::get('email-template', [EmailTemplateController::class, 'index'])->name('email-template');
        Route::get('edit-email-template/{id}', [EmailTemplateController::class, 'edit'])->name('edit-email-template');
        Route::put('update-email-template/{id}', [EmailTemplateController::class, 'update'])->name('update-email-template');

        Route::get('general-setting', [SettingController::class, 'index'])->name('general-setting');
        Route::put('update-general-setting', [SettingController::class, 'updateGeneralSetting'])->name('update-general-setting');
        Route::put('update-theme-color', [SettingController::class, 'updateThemeColor'])->name('update-theme-color');
        Route::put('update-logo-favicon', [SettingController::class, 'updateLogoFavicon'])->name('update-logo-favicon');
        Route::put('update-cookie-consent', [SettingController::class, 'updateCookieConset'])->name('update-cookie-consent');
        Route::put('update-google-recaptcha', [SettingController::class, 'updateGoogleRecaptcha'])->name('update-google-recaptcha');
        Route::put('update-facebook-comment', [SettingController::class, 'updateFacebookComment'])->name('update-facebook-comment');
        Route::put('update-tawk-chat', [SettingController::class, 'updateTawkChat'])->name('update-tawk-chat');
        Route::put('update-google-analytic', [SettingController::class, 'updateGoogleAnalytic'])->name('update-google-analytic');
        Route::put('update-custom-pagination', [SettingController::class, 'updateCustomPagination'])->name('update-custom-pagination');
        Route::put('update-social-login', [SettingController::class, 'updateSocialLogin'])->name('update-social-login');
        Route::put('update-facebook-pixel', [SettingController::class, 'updateFacebookPixel'])->name('update-facebook-pixel');
        Route::put('update-pusher', [SettingController::class, 'updatePusher'])->name('update-pusher');

        Route::resource('admin', AdminController::class);
        Route::put('admin-status/{id}', [AdminController::class, 'changeStatus'])->name('admin-status');

        Route::resource('faq', FaqController::class);
        Route::put('faq-status/{id}', [FaqController::class, 'changeStatus'])->name('faq-status');

        Route::get('customer-list', [CustomerController::class, 'index'])->name('customer-list');
        Route::get('customer-show/{id}', [CustomerController::class, 'show'])->name('customer-show');
        Route::put('customer-status/{id}', [CustomerController::class, 'changeStatus'])->name('customer-status');
        Route::delete('customer-delete/{id}', [CustomerController::class, 'destroy'])->name('customer-delete');
        Route::get('pending-customer-list', [CustomerController::class, 'pendingCustomerList'])->name('pending-customer-list');
        Route::get('send-email-to-all-customer', [CustomerController::class, 'sendEmailToAllUser'])->name('send-email-to-all-customer');
        Route::post('send-mail-to-all-user', [CustomerController::class, 'sendMailToAllUser'])->name('send-mail-to-all-user');
        Route::post('send-mail-to-single-user/{id}', [CustomerController::class, 'sendMailToSingleUser'])->name('send-mail-to-single-user');

        Route::resource('error-page', ErrorPageController::class);

        Route::get('maintainance-mode', [ContentController::class, 'maintainanceMode'])->name('maintainance-mode');
        Route::put('maintainance-mode-update', [ContentController::class, 'maintainanceModeUpdate'])->name('maintainance-mode-update');
        Route::get('topbar-contact', [ContentController::class, 'headerPhoneNumber'])->name('topbar-contact');
        Route::put('update-topbar-contact', [ContentController::class, 'updateHeaderPhoneNumber'])->name('update-topbar-contact');
        Route::get('default-avatar', [ContentController::class, 'defaultAvatar'])->name('default-avatar');
        Route::post('update-default-avatar', [ContentController::class, 'updateDefaultAvatar'])->name('update-default-avatar');
        Route::get('app-section', [ContentController::class, 'app_section'])->name('app-section');
        Route::post('update-app-section', [ContentController::class, 'update_app_section'])->name('update-app-section');

        Route::get('login-page', [ContentController::class, 'loginPage'])->name('login-page');
        Route::post('update-login-page', [ContentController::class, 'updateloginPage'])->name('update-login-page');

        Route::get('appointment-bg', [ContentController::class, 'appointment_bg'])->name('appointment-bg');
        Route::post('update-appointment-bg', [ContentController::class, 'update_appointment_bg'])->name('update-appointment-bg');


        Route::get('breadcrumb-image', [ContentController::class, 'breadcrumb_image'])->name('breadcrumb-image');
        Route::post('update-breadcrumb-image', [ContentController::class, 'update_breadcrumb_image'])->name('update-breadcrumb-image');

        Route::get('seo-setup', [ContentController::Class, 'seoSetup'])->name('seo-setup');
        Route::put('update-seo-setup/{id}', [ContentController::Class, 'updateSeoSetup'])->name('update-seo-setup');
        Route::get('get-seo-setup/{id}', [ContentController::Class, 'getSeoSetup'])->name('get-seo-setup');

        Route::get('payment-method', [PaymentMethodController::class, 'index'])->name('payment-method');
        Route::put('update-paypal', [PaymentMethodController::class, 'updatePaypal'])->name('update-paypal');
        Route::put('update-stripe', [PaymentMethodController::class, 'updateStripe'])->name('update-stripe');
        Route::put('update-razorpay', [PaymentMethodController::class, 'updateRazorpay'])->name('update-razorpay');
        Route::put('update-bank', [PaymentMethodController::class, 'updateBank'])->name('update-bank');
        Route::put('update-mollie', [PaymentMethodController::class, 'updateMollie'])->name('update-mollie');
        Route::put('update-paystack', [PaymentMethodController::class, 'updatePayStack'])->name('update-paystack');
        Route::put('update-flutterwave', [PaymentMethodController::class, 'updateflutterwave'])->name('update-flutterwave');
        Route::put('update-instamojo', [PaymentMethodController::class, 'updateInstamojo'])->name('update-instamojo');
        Route::put('update-cash-on-delivery', [PaymentMethodController::class, 'updateCashOnDelivery'])->name('update-cash-on-delivery');
        Route::put('update-sslcommerz', [PaymentMethodController::class, 'updateSslcommerz'])->name('update-sslcommerz');

        Route::resource('slider', SliderController::class);
        Route::put('slider-status/{id}', [SliderController::class, 'changeStatus'])->name('slider-status');
        Route::post('update-slider-image', [SliderController::class, 'update_slider_image'])->name('update-slider-image');
        Route::get('slider-intro', [SliderController::class, 'slider_intro'])->name('slider-intro');

        Route::resource('counter', CounterController::class);
        Route::post('update-counter-image', [CounterController::class, 'update_counter_image'])->name('update-counter-image');

        Route::get('menu-visibility', [MenuVisibilityController::class, 'index'])->name('menu-visibility');
        Route::put('update-menu-visibility/{id}', [MenuVisibilityController::class, 'update'])->name('update-menu-visibility');

        Route::get('all-order', [OrderController::class, 'index'])->name('all-order');
        Route::get('web-order', [OrderController::class, 'webOrder'])->name('web-order');
        Route::get('pending-order-count', [OrderController::class, 'getPendingOrderCount'])->name('admin.pendingOrderCount');
        Route::get('pregress-order', [OrderController::class, 'pregressOrder'])->name('pregress-order');
        Route::get('delivered-order', [OrderController::class, 'deliveredOrder'])->name('delivered-order');
        Route::get('completed-order', [OrderController::class, 'completedOrder'])->name('completed-order');
        Route::get('declined-order', [OrderController::class, 'declinedOrder'])->name('declined-order');
        Route::get('cash-on-delivery', [OrderController::class, 'cashOnDelivery'])->name('cash-on-delivery');
        Route::get('order-show/{id}', [OrderController::class, 'show'])->name('order-show');
        Route::delete('delete-order/{id}', [OrderController::class, 'destroy'])->name('delete-order');
        Route::put('update-order-status/{id}', [OrderController::class, 'updateOrderStatus'])->name('update-order-status');
        Route::post('order-print/{id}', [OrderController::class, 'printOrder'])->name('order-print');
        Route::get('order-receipt/{id}', [OrderController::class, 'viewReceipt'])->name('order-receipt');
        Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');

        Route::get('reservation', [OrderController::class, 'reservation'])->name('reservation');
        Route::get('reservation-notifications', [OrderController::class, 'reservationNotifications'])->name('reservation-notifications');
        Route::get('reservation-popup-data', [OrderController::class, 'reservationPopupData'])->name('reservation-popup-data');
        Route::post('reservation-mark-viewed', [OrderController::class, 'markReservationNotificationsViewed'])->name('reservation-mark-viewed');
        Route::put('update-reservation-status/{id}', [OrderController::class, 'update_reservation_status'])->name('update-reservation-status');
        Route:: delete('delete-reservation/{id}', [OrderController::class, 'delete_reservation'])->name('delete-reservation');

        Route::get('working-hours', [WorkingHoursController::class, 'index'])->name('working-hours');
        Route::put('update-working-hours', [WorkingHoursController::class, 'update'])->name('update-working-hours');

        Route::get('order-control', [OrderControlController::class, 'index'])->name('order-control');
        Route::put('update-order-control', [OrderControlController::class, 'update'])->name('update-order-control');

        Route::resource('coupon', CouponController::class);
        Route::put('coupon-status/{id}', [CouponController::class, 'changeStatus'])->name('coupon-status');

        Route::resource('footer', FooterController::class);
        Route::resource('social-link', FooterSocialLinkController::class);

        Route::get('admin-language', [LanguageController::class, 'adminLnagugae'])->name('admin-language');
        Route::post('update-admin-language', [LanguageController::class, 'updateAdminLanguage'])->name('update-admin-language');
        Route::get('admin-validation-language', [LanguageController::class, 'adminValidationLnagugae'])->name('admin-validation-language');
        Route::post('update-admin-validation-language', [LanguageController::class, 'updateAdminValidationLnagugae'])->name('update-admin-validation-language');

        Route::get('website-language', [LanguageController::class, 'websiteLanguage'])->name('website-language');
        Route::post('update-language', [LanguageController::class, 'updateLanguage'])->name('update-language');
        Route::get('website-validation-language', [LanguageController::class, 'websiteValidationLanguage'])->name('website-validation-language');
        Route::post('update-validation-language', [LanguageController::class, 'updateValidationLanguage'])->name('update-validation-language');

        Route::get('homepage', [HomepageController::class, 'homepage'])->name('homepage');
        Route::put('update-homepage', [HomepageController::class, 'update_homepage'])->name('update-homepage');

        Route::resource('delivery-area', DeliveryAreaCotroller::class);
    });


});


Route::get('/auth/google/callback', [LoginController::class, 'google_callback']);

Route::get('/auth/google', function () {

    return Socialite::driver('google')->redirect();
})->name('auth.google');


Route::get('/auth/facebook/callback', [LoginController::class, 'facebook_callback']);

Route::get('/auth/facebook', function () {

    return Socialite::driver('facebook')->redirect();
})->name('auth.facebook');

Route::get('/auth/update-phone', function () {
    return view('phone_number');
})->name('auth.update-phone');

Route::post('/auth/store-phone', function (Request $request) {

    $user = session()->get('user-phone');
    $request->validate([
        'phone' => 'required|numeric'
    ]);
    $user = User::where('email', $user->email)->first();
    $user->phone = $request->phone;
    $user->save();
    Auth::login($user);
    session()->forget('user-phone');
    return redirect()->route('dashboard');


})->name('auth.store-phone');


Route::get('checkout', [DeliveryCheckoutController::class, 'checkout'])->name('checkout');
Route::get('/success', [GuestPaymentController::class, 'success'])->name('success');
Route::get('/success/receipt/{order}', [GuestPaymentController::class, 'downloadReceipt'])->name('success.receipt.download');
Route::get('pickup', [GuestPaymentController::class, 'pickup'])->name('pickup');
Route::get('delivery', [GuestPaymentController::class, 'delivery'])->name('delivery');
Route::post('stripe-payment', [GuestPaymentController::class, 'stripe_payment'])->name('stripe-payment');
Route::get('/set-delivery-charge', [GuestPaymentController::class, 'set_delivery_charge'])->name('set-delivery-charge');
