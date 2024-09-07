<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCart extends Model
{
   
    protected $fillable = ['user_id','product_id','color','size','qty','price'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
