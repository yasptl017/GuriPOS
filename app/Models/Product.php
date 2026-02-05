<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Product extends Model
{
    use HasFactory;

    protected $appends = ['averageRating','totalReview','isOffer','offer'];

    public function getIsOfferAttribute()
    {
        return $this->offer_price ? true : false;
    }

    public function getOfferAttribute()
    {
        $price = $this->price;
        $offer_price = $this->offer_price ? $this->offer_price : 0;
        $offer_amount = $price - $offer_price;
        $percentage = ($offer_amount * 100) / $price;
        $percentage = round($percentage);

        return $this->offer_price ? $percentage : 0;
    }

    public function getAverageRatingAttribute()
    {
        return $this->avgReview()->avg('rating') ? : '0';
    }

    public function getTotalReviewAttribute()
    {
        return $this->avgReview()->count();
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function gallery(){
        return $this->hasMany(ProductGallery::class);
    }

    public function reviews(){
        return $this->hasMany(ProductReview::class);
    }

    public function avgReview(){
        return $this->hasMany(ProductReview::class)->where('status', 1);
    }

}
