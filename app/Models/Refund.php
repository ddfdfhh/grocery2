<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class Refund extends Model
{
    use HasFactory;
    protected $table='refund';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
    public function order():BelongsTo
    {
      return $this->belongsTo(Order::class,'order_id','id')->withDefault();
    } 
  

 

 
	public function user():BelongsTo
{
  return $this->belongsTo(User::class,'user_id','id')->withDefault()->withTrashed();
} 
 }