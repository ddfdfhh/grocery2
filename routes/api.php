<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController as ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DriverOrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\CouponController;
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
   
    Route::post("verifyOtp", "verify_otp");
    Route::post("resendOtp", "resend_otp");
    // Route::post("cg", "identify_cg");
});
Route::controller(SettingController::class)->group(function () {
    Route::get('setting', 'index');
   
  
});
 Route::controller(PaymentController::class)->group(function () {
        // Route::get('categories', 'index');
         Route::post('createOrderId', 'createOrderId');
         Route::post('storePayment', 'storePayment');

        
        
     }); 
   
    
Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'index');
    Route::get('product/{id}', 'show');
    Route::post('search', 'search');
   
});
 Route::controller(CouponController::class)->group(function () {
        Route::get('coupons', 'index');
       
      
    });
    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories', 'index');
        Route::get('category/{id}', 'show');
       
    });
     Route::get("get_content_sections", [App\Http\Controllers\Api\AppFrontendController::class,"getContentSections"]);
     Route::get("get_single_content_section/{id}", [App\Http\Controllers\Api\AppFrontendController::class,"getSingleContentSection"]);
     Route::get("collection_products/{id}", [App\Http\Controllers\Api\ProductController::class,"collection_products"]);
   
     Route::get("get_deals", [App\Http\Controllers\Api\AppFrontendController::class,"getDealsSections"]);
     Route::get("get_banners", [App\Http\Controllers\Api\AppFrontendController::class,"getBanners"]);
       Route::get("offersAndCoupons", [App\Http\Controllers\Api\CouponContrller::class,"getAllActiveOffersAndCoupons"]);
     Route::get("sendOtp/{toNumber}", [App\Http\Controllers\Api\SmsController::class,"sendOtp"]);
     Route::get("smsBalance/", [App\Http\Controllers\Api\SmsController::class,"smsBalance"]);
Route::middleware('auth:api')->group(function () {
   
    Route::controller(AuthController::class)->group(function () {
       
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::post('updateProfile', 'updateProfile');
        Route::post("updateAddress", "update_address");
        Route::post("updateDeviceToken", "updateDeviceToken");
        // Route::post("cg", "identify_cg");
    });
   
 
   Route::prefix('driver')->controller(DriverOrderController::class)->group(function () {
    Route::get('orders', 'index');
        Route::post('order_history', 'order_history');
        Route::get('orders/{id}', 'show');
         Route::get('orders/{id}', 'show');
           Route::get('return_orders/{id}', 'show_return_items');
         Route::post('order_cancel', 'order_cancel');
         Route::post('confirm_order_delivery', 'confirm_order_delivery');
      
    });
    
    Route::controller(CartController::class)->group(function () {
       // Route::get('categories', 'index');
        Route::post('save_cart', 'store');
        Route::post('cart', 'index');
        Route::delete('cart/{id}', 'destroy');
        Route::post('applyCouponCode', 'applyCouponCode');
        Route::post('removeCoupon', 'removeCoupon');
    }); 
    Route::controller(UserController::class)->group(function () {
        // Route::get('categories', 'index');
         Route::post('shipping', 'update_shipping');
        
     });
     Route::controller(OrderController::class)->group(function () {
        // Route::get('categories', 'index');
         Route::post('order', 'store');
         Route::post('cartlevelDiscount', 'getCartlevelDiscounts');
        
         Route::post('cartlevelDiscount', 'applyAndReturnCartlevelDiscounts');
       
         Route::post('order_history', 'order_history');
         Route::get('orders/{id}', 'show');
         Route::post('order_cancel', 'order_cancel');
        
        
     }); 
    
     Route::controller(SupportController::class)->group(function () {
        // Route::get('categories', 'index');
         Route::post('listCustomerQueries', 'listCustomerQueries');
         Route::post('createQuery', 'createQuery');
         Route::post('customerReply', 'customerReply');

        
        
     }); 

    
     Route::post("upload", [App\Http\Controllers\Api\ReturnController::class,"upload1"]);
     Route::post("setFCMToken", [App\Http\Controllers\Api\AuthController::class,"setFCMToken"]);
   
     
     
});

