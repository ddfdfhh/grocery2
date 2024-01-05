<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class ContentSection extends Model
{
    use SoftDeletes,HasFactory;
    protected $table='content_sections';
    public $timestamps=0;
    protected $dates = ['deleted_at'];
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
	public function  banner():BelongsTo
    {
        
        return $this->belongsTo(Banner::class,'banner_id', 'id',);
        
    }
    
  

	public function  categories():BelongsToMany
{
    
    return $this->belongsToMany(Category::class,'category_contentsection', 'contentsection_id', 'category_id');
    
}

 
	public function  products():BelongsToMany
{
    
    return $this->belongsToMany(Product::class,'contentsection_product', 'contentsection_id', 'product_id');
    
}

public function  collections():BelongsToMany
{
    
    return $this->belongsToMany(Collection::class,'contentsection_collection', 'contentsection_id', 'collection_id');
    
}
 }