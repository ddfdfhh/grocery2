<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class BulkDiscount extends Model
{
    use SoftDeletes,HasFactory;
    protected $table='bulk_discounts';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   public function product():BelongsTo
{
  return $this->belongsTo(Product::class,'product_id','id');
} 
 
	public function category():BelongsTo
{
  return $this->belongsTo(Category::class,'category_id','id');
 }
}