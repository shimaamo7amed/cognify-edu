<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ServiceCoreController;
use App\Http\Controllers\CognifyChildController;
use App\Http\Controllers\CognifyParentController;
use App\Http\Controllers\Forms\PartnerController;
use App\Http\Controllers\E_Commerce\CartController;
use App\Http\Controllers\Forms\ContactUsController;
use App\Http\Controllers\E_Commerce\OrderController;
use App\Http\Controllers\E_Commerce\ReviewController;
use App\Http\Controllers\E_Commerce\ProductController;
use App\Http\Controllers\E_Commerce\CategoryController;
use App\Http\Controllers\E_Commerce\WishlistController;
use App\Http\Controllers\ObservationChildCaseController;
use App\Http\Controllers\E_Commerce\ProductReviewController;
use App\Http\Controllers\Observation\ObservationSessionSlotController;

Route::get('events', [EventsController::class, 'allEvents']);
Route::post('/employee', [EmployeeController::class, 'EmployeesRegister']);

Route::controller(ContactUsController::class)->group(function()
{
    Route::post('/contact-us', 'ContactUS');
});

Route::controller(PartnerController::class)->group(function()
{
    Route::get('/services', 'getAllServices');
    Route::get('/service/{id}', 'getServiceById');
    Route::post('/partner', 'partnerRegister');
});


Route::controller(ServiceCoreController::class)->group(function () {
    Route::get('/serviceCore', 'getAllServices');
    Route::get('/serviceCore/{id}', 'getServiceCoreById');
});

Route::controller(CognifyParentController::class)->group(function () {
    Route::post('/register-user', 'register');
    Route::post('verify-otp', 'verifyPhoneOtp');
    Route::post('/verifyOTP', 'verifyOtp');
    Route::post('/login', 'parentLogin');
});

Route::prefix('password')->controller(PasswordController::class)->group(function () {
    Route::post('/forget-password', 'forget');
    Route::post('/validate-otp', 'validateOtp');
    Route::post('/reset-password', 'reset');
});

Route::controller(CognifyChildController::class)->group(function () {
    Route::post('/child', 'chiledcognify')->middleware('auth:sanctum');
    Route::get('/user-details', 'details')->middleware('auth:sanctum');
});

// ObservationSession

Route::controller(ObservationSessionSlotController::class)->group(function () {
    Route::get('/sessionSlots', 'allSessionSlots');
    Route::get('/session-info', 'getSessionInfo')->middleware('auth:sanctum');

});

Route::controller(ObservationChildCaseController::class)->group(function () {
    Route::post('/book-session', 'bookAndPay')->middleware('auth:sanctum');
    Route::post('/payment/observation/callback', 'handleCallback')
    ->name('observation.callback')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);


});

// E-commerce routes


Route::prefix('store')->controller(ProductController::class)->group(function () {
    Route::get('/products', 'GetAllProducts');
    Route::get('/product-details/{slug}', 'GetProductBySlug');

});



// Cart routes
Route::prefix('store')->controller(CartController::class)->group(function () {
    Route::get('/cart',  'show');
    Route::post('/cart', 'addToCart');
    Route::post('/cart/update-quantity',  'updateQuantity');
    Route::Delete('/cart/delete/{product_id?}',  'deleteCart');
    // Route::delete('cart/delete/{id}', 'deleteCart');


});
// Wishlist routes
Route::prefix('store')->controller(WishlistController::class)->group(function () {
    Route::get('/wishlist',  'index');
    Route::post('/wishlist', 'toggle');
    Route::delete('/wishlist/{id}',  'remove');
});

// Order routes
Route::controller(OrderController::class)->group(function () {
    Route::post('/order', 'createOrder');
    Route::get('/order/{id}', 'getOrder')->middleware('auth:sanctum');
    Route::post('/order/update', 'updateOrder')->middleware('auth:sanctum');
});
Route::prefix('store')->controller(ProductReviewController::class)->group(function () {
    Route::post('/review/product', 'addReview');
    Route::get('/product/orders', 'productReviews');

});

Route::controller(ReviewController::class)->group(function () {
    Route::post('/review', 'review')->middleware('auth:sanctum');
    Route::get('/review', 'getReview');
    Route::post('/review/update', 'updateReview')->middleware('auth:sanctum');
});
Route::get('/categories', [CategoryController::class, 'getAllCategory']);


















