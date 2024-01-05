<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \App\Models\Product;
use \Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        $setting = \DB::table('settings')->first();

        $category = $r->categroy_id;

        $page = $r->page;
        $orderByPrice = $r->has('orderByPrice') ? $r->orderByPrice : null;
        $orderByDiscount = $r->has('orderbyDiscount') ? $r->orderbyDiscount : null;
        $priceRange = $r->has('priceRange') ? json_decode(urldecode($r->priceRange), true) : [];
        //  dd(json_decode(urldecode($r->priceRange),true));
        $brands = $r->has('filterBybrands') ? json_decode($r->filterBybrands, true) : [];
        // Log::info('show for: {id}', ['id' => $category]);
        $brands_rows = Product::with(['brand:id,name'])->whereCategoryId($category)->get();
        $brands_to_send = [];

        foreach ($brands_rows as $h) {
            $brands_to_send[] = $h->brand->name;
        }
        // $brands_to_send=array_unique($brands_to_send);
      
        $per_page = 8;
        $min_price = Product::whereCategoryId($category)->min('sale_price');
        $max_price = Product::whereCategoryId($category)->max('sale_price');
        $list = Product::with(['brand:id,name', 'category:id,name', 'variants'])->
            when(!is_null($category), function ($query) use ($category) {
            return $query->where('category_id', $category);
        })->when(!empty($brands), function ($query) use ($brands) {
            return $query->whereHas('brand', function ($q1) use ($brands) {
                $q1->whereIn('name', $brands);
            });
        })->when(!empty($priceRange), function ($query) use ($priceRange) {
            return $query->where('sale_price', '>=', $priceRange['min'])
                ->where('sale_price', '<=', $priceRange['max']);
        })->when(!empty($orderByPrice), function ($query) use ($orderByPrice) {
            return $query->orderBy('sale_price', $orderByPrice);

        })->paginate($per_page);

        $dimensions = getThumbnailDimensions();
        $categories_with_offer = getCurrentAutomaticBulkOfferWithoutMinimumAmountSet()['categories_with_offer'];
        $products_with_offer = getCurrentAutomaticBulkOfferWithoutMinimumAmountSet()['products_with_offer'];
         $list->getCollection()->transform(function ($r) use ($setting, $categories_with_offer, $products_with_offer) {

            return modifiedProductDetail($r, $setting, $categories_with_offer, $products_with_offer);
        });
        $respl = formattedPaginatedApiResponse($list);
        $respl['meta']['minPrice'] = $min_price;
        $respl['meta']['maxPrice'] = $max_price;
        $respl['meta']['brands_list'] = $brands_to_send;
        return response()->json($respl, 200);
    }

    public function show($id)
    {
        $setting = \DB::table('settings')->first();
        $product = Product::with(['brand:id,name', 'category:id,name,sgst,cgst,igst', 'variants', 'images'])
            ->where('id', $id)->first();
        $bulk_discounts = \DB::table('coupons')->whereStatus('Active')
            ->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())
            ->where('type', 'Bulk')->whereDiscountMethod('Automatic')
            ->whereNull('minimum_order_amount')
            ->get();
        $category_dis = [];
        $prod_dis = [];

        $dimensions = getThumbnailDimensions();
        $related_products = Product::with(['brand:id,name', 'variants', 'images'])
            ->whereCategoryId($product->category_id)->where('id', '!=', $id)->limit(20)->get();
        $categories_with_offer = getCurrentAutomaticBulkOfferWithoutMinimumAmountSet()['categories_with_offer'];
        $products_with_offer = getCurrentAutomaticBulkOfferWithoutMinimumAmountSet()['products_with_offer'];

        $product = modifiedProductDetail($product, $setting, $categories_with_offer, $products_with_offer, true);
        if (!empty(count($related_products) > 0)) {
            $related_products = $related_products->map(function ($r) use ($setting, $categories_with_offer, $products_with_offer) {

                return modifiedProductDetail($r, $setting, $categories_with_offer, $products_with_offer);
            });

        }

        return response()->json(['data' => $product, 'related_products' => $related_products], 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $r)
    {
        $search_string = trim($r->search_string);
        // dd($search_string);
        // $result=\App\Models\Product::whereName('')
        $query = \App\Models\Product::with('category:id,name')->where('name', 'like', '%' . $search_string . '%')->get();
        //$products=[];

        foreach ($query as $t) {
            $t->cat_name = $t->category->name;
            unset($t->category);
            $r->thumbnail = new \stdClass;
            if ($t->image) {
                $thum = getThumbnailsFromImage($t->image);
                $t->thumbnail = !empty($thum) ? $thum['tiny'] : '';
            }

        }
        return response()->json(['data' => $query], 200);

    }
    public function collection_products(Request $r, $id)
    {
        $setting = \DB::table('settings')->first();
        $category = $r->categroy_id;
        $orderByPrice = $r->has('orderByPrice') ? $r->orderByPrice : null;
        $orderByDiscount = $r->has('orderbyDiscount') ? $r->orderbyDiscount : null;
        $priceRange = $r->has('priceRange') ? json_decode(urldecode($r->priceRange), true) : [];
        //  dd(json_decode(urldecode($r->priceRange),true));
        $brands = $r->has('filterBybrands') ? json_decode($r->filterBybrands, true) : [];
        $categories_with_offer = getCurrentAutomaticBulkOfferWithoutMinimumAmountSet()['categories_with_offer'];
        $products_with_offer = getCurrentAutomaticBulkOfferWithoutMinimumAmountSet()['products_with_offer'];
     

        $list = \App\Models\Collection::whereId($id)->first();
        $product_ids = array_column(json_decode($list->product_id, true), 'id');
        $list = Product::with(['brand:id,name', 'category:id,name', 'variants'])->
            when(!is_null($category), function ($query) use ($category) {
            return $query->where('category_id', $category);
        })->when(!empty($brands), function ($query) use ($brands) {
            return $query->whereHas('brand', function ($q1) {
                $q1->whereIn('name', $brands);
            });
        })->when(!empty($priceRange), function ($query) use ($priceRange) {
            return $query->where('price', '>=', $priceRange['min'])
                ->where('price', '<=', $priceRange['max']);
        })->when(!empty($orderByPrice), function ($query) use ($orderByPrice) {
            return $query->orderBy('price', $orderByPrice);

        })->whereIn('id', $product_ids)->get();

        $dimensions = getThumbnailDimensions();
        $list = $list->map(function ($r) use ($setting, $categories_with_offer,$products_with_offer) {
            return modifiedProductDetail($r, $setting, $categories_with_offer,$products_with_offer);

        });

        return response()->json(['data' => $list], 200);
    }
}
