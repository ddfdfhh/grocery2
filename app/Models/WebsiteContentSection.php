<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class WebsiteContentSection extends Model
{
    use SoftDeletes,HasFactory;
    protected $table='website_content_sections';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
    
    

    public function banner():BelongsTo
  {
    return $this->belongsTo(WebsiteBanner::class,'banner_id','id')->withDefault()->withTrashed();
  } 


    public function categories(): BelongsToMany
    {

        return $this->belongsToMany(Category::class, 'website_category_contentsection', 'contentsection_id', 'category_id');

    }

    public function products(): BelongsToMany
    {

        return $this->belongsToMany(Product::class, 'website_contentsection_product', 'contentsection_id', 'product_id');

    }

    public function collections(): BelongsToMany
    {

        return $this->belongsToMany(Collection::class, 'website_contentsection_collection', 'contentsection_id', 'collection_id');

    }
}
 