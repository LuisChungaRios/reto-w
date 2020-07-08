<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $IGV = 0.18;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPriceWithDiscountAndIGVtAttribute()
    {
        $price = $this->price;

        if ($this->discount != 0) {
            $price = $this->price - ( $this->price * $this->discount);
        }
        return  $price + ($price * $this->IGV);
    }

}
