<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class WebsiteBanner extends Model
{
    use SoftDeletes,HasFactory;
    protected $table='website_banners';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
  

	public function collection():BelongsTo
{
  return $this->belongsTo(Collection::class,'collection_id','id')->withDefault()->withTrashed();
} 
 
	public function carousel_images():HasMany
{
return $this->hasMany(WebsiteCarouselImage::class,'banner_id','id');
}
  
 }