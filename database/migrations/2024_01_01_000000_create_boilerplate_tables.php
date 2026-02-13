<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates all tables that came pre-built with the Laravel boilerplate/starter kit.
 * Every table is guarded with hasTable() so this is safe to run on an existing DB.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->integer('admin_type')->default(0);
                $table->string('name');
                $table->string('email')->unique();
                $table->string('image', 191)->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('remember_token', 100)->nullable();
                $table->integer('status')->default(1);
                $table->string('forget_password_token', 191)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('app_name')->nullable();
                $table->string('logo')->nullable();
                $table->string('footer_logo')->nullable();
                $table->string('favicon')->nullable();
                $table->string('contact_email', 191)->nullable();
                $table->integer('enable_save_contact_message')->default(1);
                $table->string('text_direction')->default('LTR');
                $table->string('timezone')->nullable();
                $table->string('currency_name', 191)->nullable();
                $table->string('currency_icon', 191)->nullable();
                $table->double('currency_rate')->default(1);
                $table->string('theme_one', 191);
                $table->string('theme_two', 191);
                $table->string('slider_background')->nullable();
                $table->string('slider_header_one')->nullable();
                $table->string('slider_header_two')->nullable();
                $table->text('slider_description')->nullable();
                $table->string('slider_offer_text')->nullable();
                $table->string('slider_offer_image')->nullable();
                $table->string('counter_background')->nullable();
                $table->string('app_title')->nullable();
                $table->text('app_description')->nullable();
                $table->string('app_image')->nullable();
                $table->text('app_store_link')->nullable();
                $table->text('play_store_link')->nullable();
                $table->string('app_background_one')->nullable();
                $table->string('app_background_two')->nullable();
                $table->string('partner_background')->nullable();
                $table->string('default_avatar')->nullable();
                $table->string('breadcrumb_image')->nullable();
                $table->string('login_page_image')->nullable();
                $table->string('colorPrimary')->default('#eb0029');
                $table->string('gradiantBg1')->default('rgb(156, 3, 30)');
                $table->string('gradiantBg2')->default('rgba(156, 3, 30, 1)');
                $table->string('gradiantBg3')->default('rgba(235, 0, 41, 1)');
                $table->string('gradiantHoverBg1')->default('rgb(235, 0, 41)');
                $table->string('gradiantHoverBg2')->default('rgba(235, 0, 41, 1)');
                $table->string('gradiantHoverBg3')->default('rgba(156, 3, 30, 1)');
                $table->string('topbar_social_icon_color')->default('#ca0628');
                $table->string('footer_color')->default('#b90424fa');
                $table->string('appointment_bg')->nullable();
                $table->string('kitchen_printer')->nullable();
                $table->string('desk_printer')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug');
                $table->integer('status')->default(0);
                $table->integer('show_homepage')->default(1);
                $table->integer('home_sort_order')->default(0);
                $table->integer('pos_sort_order')->default(0);
                $table->integer('receipt_sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug');
                $table->string('thumb_image');
                $table->integer('category_id');
                $table->text('short_description');
                $table->longText('long_description');
                $table->string('video_link')->nullable();
                $table->string('sku')->nullable();
                $table->text('seo_title');
                $table->text('seo_description');
                $table->double('price');
                $table->double('offer_price')->nullable();
                $table->text('tags')->nullable();
                $table->tinyInteger('show_homepage')->default(0);
                $table->tinyInteger('status')->default(0);
                $table->integer('today_special')->default(0);
                $table->text('size_variant')->nullable();
                $table->text('optional_item')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_id');
                $table->integer('user_id');
                $table->double('grand_total')->default(0);
                $table->double('sub_total');
                $table->integer('product_qty');
                $table->string('payment_method')->nullable();
                $table->text('print_receipt')->nullable();
                $table->integer('payment_status')->default(0);
                $table->string('payment_approval_date')->nullable();
                $table->string('transection_id')->nullable();
                $table->double('delivery_charge')->default(0);
                $table->double('coupon_price')->default(0);
                $table->string('coupon_name')->nullable();
                $table->integer('order_status')->default(0);
                $table->string('order_type')->default('Pickup');
                $table->string('order_approval_date')->nullable();
                $table->string('order_delivered_date')->nullable();
                $table->string('order_completed_date')->nullable();
                $table->string('order_declined_date')->nullable();
                $table->integer('cash_on_delivery')->default(0);
                $table->string('mobile', 2000)->default('__');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('order_products')) {
            Schema::create('order_products', function (Blueprint $table) {
                $table->id();
                $table->integer('order_id');
                $table->integer('product_id');
                $table->double('optional_price')->default(0);
                $table->string('product_name');
                $table->double('unit_price')->default(0);
                $table->integer('qty');
                $table->string('product_size')->nullable();
                $table->text('optional_item')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('order_addresses')) {
            Schema::create('order_addresses', function (Blueprint $table) {
                $table->id();
                $table->integer('order_id');
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('latitude', 191)->nullable();
                $table->string('longitude', 191)->nullable();
                $table->string('delivery_time')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('delivery_area_id')->nullable()->default(1);
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('type')->nullable();
                $table->string('default_address', 11)->default('No');
                $table->string('latitude')->nullable();
                $table->string('longitude')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_galleries')) {
            Schema::create('product_galleries', function (Blueprint $table) {
                $table->id();
                $table->integer('product_id');
                $table->string('image');
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_reviews')) {
            Schema::create('product_reviews', function (Blueprint $table) {
                $table->id();
                $table->integer('product_id');
                $table->integer('user_id')->default(0);
                $table->text('review');
                $table->integer('rating');
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sliders')) {
            Schema::create('sliders', function (Blueprint $table) {
                $table->id();
                $table->string('title_one')->nullable();
                $table->string('title_two')->nullable();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->text('link')->nullable();
                $table->integer('status')->default(0);
                $table->integer('serial')->default(0);
                $table->string('offer_text')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('counters')) {
            Schema::create('counters', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('quantity');
                $table->string('icon');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('icon');
                $table->text('description');
                $table->tinyInteger('status');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('testimonials')) {
            Schema::create('testimonials', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('designation');
                $table->string('image');
                $table->string('product_image')->nullable();
                $table->string('rating');
                $table->text('comment');
                $table->integer('status');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('our_chefs')) {
            Schema::create('our_chefs', function (Blueprint $table) {
                $table->id();
                $table->string('image');
                $table->string('name');
                $table->string('designation');
                $table->string('facebook')->nullable();
                $table->string('twitter')->nullable();
                $table->string('linkedin')->nullable();
                $table->string('instagram')->nullable();
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->string('question')->nullable();
                $table->text('answer')->nullable();
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code');
                $table->string('min_purchase_price');
                $table->integer('offer_type')->default(0);
                $table->double('discount')->default(0);
                $table->integer('max_quantity')->default(0);
                $table->string('expired_date', 191);
                $table->integer('apply_qty')->default(0);
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('subscribers')) {
            Schema::create('subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('email');
                $table->integer('status')->default(0);
                $table->text('verified_token')->nullable();
                $table->integer('is_verified')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('subject');
                $table->text('message');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('contact_pages')) {
            Schema::create('contact_pages', function (Blueprint $table) {
                $table->id();
                $table->string('image')->nullable();
                $table->text('email')->nullable();
                $table->string('address')->nullable();
                $table->text('phone')->nullable();
                $table->text('map')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('about_us')) {
            Schema::create('about_us', function (Blueprint $table) {
                $table->id();
                $table->longText('about_us');
                $table->string('about_us_short_title')->nullable();
                $table->string('about_us_long_title')->nullable();
                $table->string('about_us_image')->nullable();
                $table->string('author_name')->nullable();
                $table->text('author_comment')->nullable();
                $table->string('experience_year')->nullable();
                $table->string('experience_text')->nullable();
                $table->string('item1_title')->nullable();
                $table->text('item1_description')->nullable();
                $table->string('item2_title')->nullable();
                $table->text('item2_description')->nullable();
                $table->string('item3_title')->nullable();
                $table->text('item3_description')->nullable();
                $table->string('vision_title')->nullable();
                $table->text('vision_description')->nullable();
                $table->string('mission_title')->nullable();
                $table->text('mission_description')->nullable();
                $table->string('goal_title')->nullable();
                $table->text('goal_description')->nullable();
                $table->string('vision_bg')->nullable();
                $table->string('why_choose_us_short_title')->nullable();
                $table->string('why_choose_us_long_title')->nullable();
                $table->text('why_choose_us_description')->nullable();
                $table->string('why_choose_us_background')->nullable();
                $table->string('title_one')->nullable();
                $table->string('description_one')->nullable();
                $table->string('title_two')->nullable();
                $table->string('description_two')->nullable();
                $table->string('title_three')->nullable();
                $table->string('description_three')->nullable();
                $table->string('title_four')->nullable();
                $table->string('description_four')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('homepages')) {
            Schema::create('homepages', function (Blueprint $table) {
                $table->id();
                $table->string('today_special_short_title');
                $table->string('today_special_long_title');
                $table->text('today_special_description');
                $table->integer('today_special_item');
                $table->string('today_special_image');
                $table->tinyInteger('today_special_status')->default(0);
                $table->string('menu_short_title');
                $table->string('menu_long_title');
                $table->text('menu_description');
                $table->integer('menu_item');
                $table->string('menu_left_image');
                $table->string('menu_right_image');
                $table->tinyInteger('menu_status')->default(0);
                $table->tinyInteger('advertisement_status')->default(0);
                $table->integer('total_advertisement_item');
                $table->string('chef_short_title');
                $table->string('chef_long_title');
                $table->text('chef_description');
                $table->integer('chef_item');
                $table->string('chef_left_image');
                $table->string('chef_right_image');
                $table->tinyInteger('chef_status')->default(0);
                $table->tinyInteger('mobile_app_status')->default(0);
                $table->tinyInteger('counter_status')->default(0);
                $table->string('testimonial_short_title');
                $table->string('testimonial_long_title');
                $table->text('testimonial_description');
                $table->integer('testimonial_item');
                $table->tinyInteger('testimonial_status')->default(0);
                $table->string('blog_short_title');
                $table->string('blog_long_title');
                $table->text('blog_description');
                $table->integer('blog_item');
                $table->tinyInteger('blog_status')->default(0);
                $table->string('blog_background');
                $table->string('blog_background_2')->nullable();
                $table->tinyInteger('why_choose_us_status')->default(0);
                $table->tinyInteger('video_section_status')->default(0);
                $table->integer('service_status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('footers')) {
            Schema::create('footers', function (Blueprint $table) {
                $table->id();
                $table->text('about_us')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('address')->nullable();
                $table->string('first_column')->nullable();
                $table->string('second_column')->nullable();
                $table->string('third_column')->nullable();
                $table->string('copyright', 191)->nullable();
                $table->string('footer_background', 191)->nullable();
                $table->string('footer_background_2')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('footer_social_links')) {
            Schema::create('footer_social_links', function (Blueprint $table) {
                $table->id();
                $table->string('link')->nullable();
                $table->string('icon')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blog_categories')) {
            Schema::create('blog_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug');
                $table->tinyInteger('status');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blogs')) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->id();
                $table->integer('admin_id');
                $table->string('title');
                $table->string('slug');
                $table->integer('blog_category_id');
                $table->string('image');
                $table->longText('description');
                $table->text('short_description')->nullable();
                $table->integer('views')->default(0);
                $table->string('seo_title');
                $table->string('seo_description');
                $table->integer('status')->default(0);
                $table->integer('show_homepage')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blog_comments')) {
            Schema::create('blog_comments', function (Blueprint $table) {
                $table->id();
                $table->integer('blog_id');
                $table->string('name');
                $table->string('email');
                $table->text('comment');
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('popular_posts')) {
            Schema::create('popular_posts', function (Blueprint $table) {
                $table->id();
                $table->integer('blog_id');
                $table->integer('status');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('seo_settings')) {
            Schema::create('seo_settings', function (Blueprint $table) {
                $table->id();
                $table->text('page_name')->nullable();
                $table->text('seo_title')->nullable();
                $table->text('seo_description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('custom_pages')) {
            Schema::create('custom_pages', function (Blueprint $table) {
                $table->id();
                $table->longText('page_name');
                $table->string('slug', 191);
                $table->longText('description');
                $table->tinyInteger('status');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('custom_paginations')) {
            Schema::create('custom_paginations', function (Blueprint $table) {
                $table->id();
                $table->string('page_name');
                $table->integer('qty')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('error_pages')) {
            Schema::create('error_pages', function (Blueprint $table) {
                $table->id();
                $table->string('page_name');
                $table->string('image')->nullable();
                $table->string('header');
                $table->string('description');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('terms_and_conditions')) {
            Schema::create('terms_and_conditions', function (Blueprint $table) {
                $table->id();
                $table->longText('terms_and_condition')->nullable();
                $table->longText('privacy_policy')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('maintainance_texts')) {
            Schema::create('maintainance_texts', function (Blueprint $table) {
                $table->id();
                $table->integer('status')->default(0);
                $table->string('image');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cookie_consents')) {
            Schema::create('cookie_consents', function (Blueprint $table) {
                $table->id();
                $table->integer('status')->default(1);
                $table->string('border')->nullable();
                $table->string('corners')->nullable();
                $table->string('background_color')->nullable();
                $table->string('text_color')->nullable();
                $table->string('border_color')->nullable();
                $table->string('btn_bg_color')->nullable();
                $table->string('btn_text_color')->nullable();
                $table->text('message')->nullable();
                $table->string('link_text')->nullable();
                $table->string('btn_text')->nullable();
                $table->string('link')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('menu_visibilities')) {
            Schema::create('menu_visibilities', function (Blueprint $table) {
                $table->id();
                $table->string('menu_name')->nullable();
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('banner_images')) {
            Schema::create('banner_images', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('title2')->nullable();
                $table->text('link')->nullable();
                $table->string('image')->nullable();
                $table->string('description')->nullable();
                $table->integer('status')->default(0);
                $table->integer('serial');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('email_configurations')) {
            Schema::create('email_configurations', function (Blueprint $table) {
                $table->id();
                $table->tinyInteger('mail_type')->nullable();
                $table->string('mail_host')->nullable();
                $table->string('mail_port')->nullable();
                $table->string('email')->nullable();
                $table->string('smtp_username')->nullable();
                $table->string('smtp_password')->nullable();
                $table->string('mail_encryption')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('email_templates')) {
            Schema::create('email_templates', function (Blueprint $table) {
                $table->id();
                $table->text('name')->nullable();
                $table->text('subject')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('social_login_information')) {
            Schema::create('social_login_information', function (Blueprint $table) {
                $table->id();
                $table->integer('is_facebook')->default(0);
                $table->text('facebook_client_id')->nullable();
                $table->text('facebook_secret_id')->nullable();
                $table->integer('is_gmail')->default(0);
                $table->text('gmail_client_id')->nullable();
                $table->text('gmail_secret_id')->nullable();
                $table->text('facebook_redirect_url')->nullable();
                $table->text('gmail_redirect_url')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('facebook_comments')) {
            Schema::create('facebook_comments', function (Blueprint $table) {
                $table->id();
                $table->string('app_id')->nullable();
                $table->integer('comment_type')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('facebook_pixels')) {
            Schema::create('facebook_pixels', function (Blueprint $table) {
                $table->id();
                $table->integer('status')->default(0);
                $table->string('app_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('google_analytics')) {
            Schema::create('google_analytics', function (Blueprint $table) {
                $table->id();
                $table->string('analytic_id')->nullable();
                $table->integer('status');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('google_recaptchas')) {
            Schema::create('google_recaptchas', function (Blueprint $table) {
                $table->id();
                $table->string('site_key')->nullable();
                $table->string('secret_key')->nullable();
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tawk_chats')) {
            Schema::create('tawk_chats', function (Blueprint $table) {
                $table->id();
                $table->string('chat_link')->nullable();
                $table->string('widget_id')->nullable();
                $table->string('property_id')->nullable();
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('currencies')) {
            Schema::create('currencies', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 3);
                $table->string('name', 50);
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
                $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
            });
        }

        if (!Schema::hasTable('currency_countries')) {
            Schema::create('currency_countries', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('code', 2);
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
                $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
            });
        }

        // Payment gateway tables
        if (!Schema::hasTable('bank_payments')) {
            Schema::create('bank_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('status')->default(0);
                $table->text('account_info')->nullable();
                $table->integer('cash_on_delivery_status')->default(1);
                $table->string('handcash_payment_page_image')->nullable();
                $table->string('bank_payment_page_image')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('stripe_payments')) {
            Schema::create('stripe_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('status')->default(0);
                $table->text('stripe_key')->nullable();
                $table->text('stripe_secret')->nullable();
                $table->string('country_code', 10);
                $table->string('currency_code', 10);
                $table->double('currency_rate');
                $table->string('payment_page_image')->nullable();
            });
        }

        if (!Schema::hasTable('paypal_payments')) {
            Schema::create('paypal_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('status')->default(0);
                $table->string('account_mode')->nullable();
                $table->text('client_id')->nullable();
                $table->text('secret_id')->nullable();
                $table->string('country_code', 191);
                $table->string('currency_code', 191);
                $table->double('currency_rate');
                $table->string('payment_page_image')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('razorpay_payments')) {
            Schema::create('razorpay_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('status')->default(0);
                $table->string('name')->nullable();
                $table->double('currency_rate')->default(1);
                $table->string('country_code', 191);
                $table->string('currency_code', 191);
                $table->string('description')->nullable();
                $table->string('image')->nullable();
                $table->string('color')->nullable();
                $table->text('key')->nullable();
                $table->text('secret_key')->nullable();
                $table->string('payment_page_image')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('flutterwaves')) {
            Schema::create('flutterwaves', function (Blueprint $table) {
                $table->id();
                $table->text('public_key');
                $table->text('secret_key');
                $table->double('currency_rate')->default(1);
                $table->string('country_code', 191);
                $table->string('currency_code', 191);
                $table->string('title');
                $table->string('logo');
                $table->integer('status')->default(1);
                $table->string('payment_page_image')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('paystack_and_mollies')) {
            Schema::create('paystack_and_mollies', function (Blueprint $table) {
                $table->id();
                $table->string('mollie_key')->nullable();
                $table->integer('mollie_status')->default(0);
                $table->double('mollie_currency_rate')->default(1);
                $table->string('paystack_public_key')->nullable();
                $table->string('paystack_secret_key')->nullable();
                $table->double('paystack_currency_rate')->default(1);
                $table->integer('paystack_status')->default(0);
                $table->string('mollie_country_code', 191);
                $table->string('mollie_currency_code', 191);
                $table->string('paystack_country_code', 191);
                $table->string('paystack_currency_code', 191);
                $table->string('paystack_payment_page_image')->nullable();
                $table->string('mollie_payment_page_image')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sslcommerz_payments')) {
            Schema::create('sslcommerz_payments', function (Blueprint $table) {
                $table->id();
                $table->text('store_id');
                $table->text('store_password');
                $table->string('mode');
                $table->string('currency_rate');
                $table->string('country_code');
                $table->string('currency_code');
                $table->integer('status')->default(1);
                $table->string('payment_page_image')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('instamojo_payments')) {
            Schema::create('instamojo_payments', function (Blueprint $table) {
                $table->id();
                $table->text('api_key');
                $table->text('auth_token');
                $table->string('currency_rate')->default('1');
                $table->string('account_mode')->default('Sandbox');
                $table->integer('status')->default(1);
                $table->string('payment_page_image')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('paymongo_payments')) {
            Schema::create('paymongo_payments', function (Blueprint $table) {
                $table->id();
                $table->string('secret_key');
                $table->string('public_key');
                $table->integer('status')->default(0);
                $table->double('currency_rate')->default(1);
                $table->string('country_code')->nullable();
                $table->string('currency_code')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('eway_payments')) {
            Schema::create('eway_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('status')->default(0);
                $table->text('eway_key')->nullable();
                $table->text('eway_pass')->nullable();
                $table->string('country_code', 10);
                $table->string('currency_code', 10);
                $table->double('currency_rate');
                $table->string('payment_page_image')->nullable();
            });
        }

        if (!Schema::hasTable('shoppingcart')) {
            Schema::create('shoppingcart', function (Blueprint $table) {
                $table->string('identifier');
                $table->string('instance');
                $table->longText('content');
                $table->timestamps();
                $table->primary(['identifier', 'instance']);
            });
        }

        if (!Schema::hasTable('wishlists')) {
            Schema::create('wishlists', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('product_id');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Drop in reverse order of dependencies
        $tables = [
            'wishlists', 'shoppingcart', 'eway_payments', 'paymongo_payments',
            'instamojo_payments', 'sslcommerz_payments', 'paystack_and_mollies',
            'flutterwaves', 'razorpay_payments', 'paypal_payments', 'stripe_payments',
            'bank_payments', 'currency_countries', 'currencies', 'tawk_chats',
            'google_recaptchas', 'google_analytics', 'facebook_pixels',
            'facebook_comments', 'social_login_information', 'email_templates',
            'email_configurations', 'banner_images', 'menu_visibilities',
            'cookie_consents', 'maintainance_texts', 'terms_and_conditions',
            'error_pages', 'custom_paginations', 'custom_pages', 'seo_settings',
            'popular_posts', 'blog_comments', 'blogs', 'blog_categories', 'footers',
            'footer_social_links', 'homepages', 'faqs', 'coupons', 'subscribers',
            'contact_messages', 'contact_pages', 'about_us', 'services',
            'testimonials', 'our_chefs', 'counters', 'sliders',
            'product_reviews', 'product_galleries', 'addresses', 'order_addresses',
            'order_products', 'orders', 'products', 'categories', 'settings', 'admins',
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
