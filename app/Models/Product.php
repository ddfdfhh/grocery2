<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    protected $table='products';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
  /*  protected $casts = [ 'price' => 'float',
     'sale_price' => 'float',
     'maxQuantityAllowed'=>'integer',
     'minimumQtyAlert'=>'integer',
     'perQuantityOf'=>'integer',
     'quantity'=>'integer',
     'vendorId'=>'integer',
     'brandId'=>'integer',
     'categoryId'=>'integer',
    'discount' => 'float',
     'sgst' => 'float',
     'cgst' => 'float',
     'igst' => 'float',
     'perPrice' => 'float',
     'packageWeight' => 'float',
    
   ];
  */

	public function category():BelongsTo
{
  return $this->belongsTo(Category::class,'category_id','id')->withDefault();
} 
public function brand():BelongsTo
{
  return $this->belongsTo(Brand::class,'brand_id','id')->withDefault();
} 
	public function product_images():HasMany
{
return $this->hasMany(ProductImage::class,'product_id','id');
}
public function images():HasMany
{
return $this->hasMany(ProductImage::class,'product_id','id');
}
public function variants():HasMany
{
return $this->hasMany(ProductVariant::class,'product_id','id');
}
  
 }