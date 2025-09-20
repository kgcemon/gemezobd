<?php

use App\Http\Controllers\admin\CategoriesController;
use App\Http\Controllers\admin\CodesController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\OrdersController;
use App\Http\Controllers\admin\PaymentMethodSettingController;
use App\Http\Controllers\admin\PaymentSMSController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SliderController;
use App\Http\Controllers\admin\SocialLinkController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\VariantController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\NoticeUpdateController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.auth.login');
});

// Authenticated Admin Routes
Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);

    // Profile Page
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');

    // Orders
    Route::get('orders', [\App\Http\Controllers\admin\OrdersController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\admin\OrdersController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}', [\App\Http\Controllers\admin\OrdersController::class, 'update'])->name('orders.update');
    Route::get('admin/orders/{order}', [\App\Http\Controllers\admin\OrdersController::class, 'show'])->name('admin.orders.show');
    Route::post('admin/orders/update/{id}', [\App\Http\Controllers\admin\OrdersController::class, 'edit'])->name('orders.edits');
    Route::get('admin/orders/edit/{id}', [\App\Http\Controllers\admin\OrdersController::class, 'editFrom'])->name('orders.edit');
    Route::post('/admin/orders/bulk-action', [OrdersController::class, 'bulkAction'])->name('orders.bulkAction');




    Route::resource('variant', VariantController::class);
    Route::resource('categories', CategoriesController::class);
    Route::get('variants/{id}', [VariantController::class, 'variant'])->name('variant');


    Route::resource('sliders', SliderController::class);
    Route::resource('users', UsersController::class);


    //codes
    Route::resource('codes', CodesController::class);
    Route::get('codes/{id}', [CodesController::class, 'code'])->name('code');
    Route::get('code/{id}', [CodesController::class, 'singleCode']);

    //payment Setting
    Route::resource('payment-methods', PaymentMethodSettingController::class);
    Route::post('payment-methods/{id}/toggle-status', [PaymentMethodSettingController::class, 'toggleStatus'])->name('payment-methods.toggleStatus');
    Route::post('payment-methods/{id}/copy', [PaymentMethodSettingController::class, 'copyNumber'])->name('payment-methods.copy');


    //paymentSMS
    //paymentSMS
    Route::get('/payment-sms', [PaymentSmsController::class, 'index'])->name('sms');
    Route::post('/sms/add', [PaymentSmsController::class, 'addSms'])->name('sms.add');
    Route::put('/sms/update-status', [PaymentSmsController::class, 'updateStatus'])->name('sms.update-status');
    Route::delete('/sms/{id}', [PaymentSmsController::class, 'delete'])->name('sms.delete');


    //Social
    Route::resource('social-links', SocialLinkController::class);

    Route::get('/send-offer', [OfferController::class, 'index'])->name('offer.index');
    Route::post('/send-offer', [OfferController::class, 'send'])->name('offer.sends');



    //notice update
    Route::get('/notice', [NoticeUpdateController::class, 'index'])->name('notice.index');
    Route::post('/notice/store', [NoticeUpdateController::class, 'store'])->name('notice.store');
    Route::delete('/notice/{id}', [NoticeUpdateController::class, 'destroy'])->name('notice.destroy');


});

// Fallback Route for 404
//Route::fallback(function () {
//    return redirect()->route('admin.dashboard')->with('error', 'Page not found.');
//});

Route::get('auto-top-up-cron',[CronJobController::class,'freeFireAutoTopUpJob']);

// Authentication Routes
require __DIR__ . '/auth.php';
