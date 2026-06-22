<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DigitalProductController;
use App\Http\Controllers\Admin\DigitalServiceController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\ProfileController as UserProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\DigitalProductController as ProductController;
use App\Http\Controllers\DigitalServiceController as ServiceController;
use App\Http\Controllers\BlogController as ForntBlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class,'index'])->name('home');

Route::get('/admin', function () {
    return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('admin.login');
});

Route::get('/mail-champ', function() {
    return view('mail-champ.index');
});

Route::get('/account', [UserProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/account', [UserProfileController::class, 'update'])->name('profile.update');
Route::delete('/account', [UserProfileController::class, 'destroy'])->name('profile.destroy');

Route::get('/products', [ProductController::class, 'index'])->name('products.listing');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/services', [ServiceController::class, 'index'])->name('services.listing');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/checkout', [CartController::class, 'checkoutIndex'])->name('checkout');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/product/add-to-cart', [CartController::class, 'addToCart'])->name('addtocart');

Route::post('/order/store', [OrderController::class, 'store'])->name('orders.store');
Route::get('/order/thank-you', [OrderController::class, 'thankYouIndex'])->name('orders.thank.you');
Route::post('/order/order-item-list', [OrderController::class, 'getOrderItemList'])->middleware('auth')->name('order.item.list');

Route::get('/blogs', [ForntBlogController::class, 'index'])->name('blogs.listing');
Route::get('/blogs/{slug}', [ForntBlogController::class, 'show'])->name('blogs.show');

Route::prefix('admin')->name('admin.')->middleware(['auth','verified','check_is_agent_is_Admin'])->group(function () {
    Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* users */
    Route::resource('users', UserController::class)->names('users');
    Route::post('users/get-data', [UserController::class, 'getData'])->name('users.getData');
    Route::post('users/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
    Route::post('users/update-status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::post('users/check-email', [UserController::class, 'checkEmail'])->name('users.check.email');
    Route::post('users/check-phone', [UserController::class, 'checkPhone'])->name('users.check.phone');

    Route::post('ajax/common/get-countries', [CommonController::class, 'getCountries'])->name('common.getCountries');
    Route::post('ajax/common/get-states', [CommonController::class, 'getStates'])->name('common.getStates');
    Route::post('ajax/common/get-cities', [CommonController::class, 'getCities'])->name('common.getCities');
    Route::post('ajax/custom-fields/get-field-type-data', [CommonController::class, 'getFieldTypeData'])->name('custom.fields.getFieldTypeData');
    Route::post('ajax/get-categories', [CommonController::class, 'getCategories'])->name('ajax.categories');

    /* roles */
    Route::resource('roles', RoleController::class)->names('roles');
    Route::post('roles/get-data', [RoleController::class, 'getData'])->name('roles.getData');
    Route::post('roles/update-status', [RoleController::class, 'updateStatus'])->name('roles.updateStatus');
    Route::post('roles/check-role', [RoleController::class, 'checkRole'])->name('validate.role');

    /* permissions */
    Route::resource('permissions', PermissionController::class)->names('permissions');

    /* locations modules */
    Route::resource('countries', CountryController::class)->names('countries');
    Route::resource('states', StateController::class)->names('states');
    Route::resource('cities', CityController::class)->names('cities');

    Route::resource('categories', CategoryController::class)->names('categories');
    Route::post('categories/get-data', [CategoryController::class, 'getData'])->name('categories.getData');
    Route::post('categories/restore/{id}', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::post('categories/update-status', [CategoryController::class, 'updateStatus'])->name('categories.updateStatus');
    Route::post('categories/check-categories', [CategoryController::class, 'checkCategoryName'])->name('categories.check.category.name');

    Route::resource('digital-products', DigitalProductController::class)->names('digital.products');
    Route::post('digital-products/get-data', [DigitalProductController::class, 'getData'])->name('digital.products.getData');
    Route::post('digital-products/restore/{id}', [DigitalProductController::class, 'restore'])->name('digital.products.restore');
    Route::post('digital-products/update-status', [DigitalProductController::class, 'updateStatus'])->name('digital.products.updateStatus');

    Route::resource('digital-services', DigitalServiceController::class)->names('digital.services');
    Route::post('digital-services/get-data', [DigitalServiceController::class, 'getData'])->name('digital.services.getData');
    Route::post('digital-services/restore/{id}', [DigitalServiceController::class, 'restore'])->name('digital.services.restore');
    Route::post('digital-services/update-status', [DigitalServiceController::class, 'updateStatus'])->name('digital.services.updateStatus');
    Route::post('digital-services/check-services-name', [DigitalServiceController::class, 'checkServiceName'])->name('digital.services.check.name');
    Route::post('digital-services/check-services-sku', [DigitalServiceController::class, 'checkServiceSku'])->name('digital.services.check.sku');

    Route::resource('blogs', BlogController::class)->names('blogs');
    Route::post('blogs/get-data', [BlogController::class, 'getData'])->name('blogs.getData');
    Route::post('blogs/restore/{id}', [BlogController::class, 'restore'])->name('blogs.restore');
    Route::post('blogs/update-status', [BlogController::class, 'updateStatus'])->name('blogs.updateStatus');

    Route::resource('wallets', WalletController::class)->names('wallets');
    Route::post('wallets/get-data', [WalletController::class, 'getData'])->name('wallets.getData');
    Route::post('wallets/get-transaction-histoty', [WalletController::class, 'getTransactionHistoty'])->name('wallets.getTransactionHistoty');
    Route::get('wallets/action/{id}/{user_id}/{action}', [WalletController::class, 'action'])->name('wallets.action');

    /* Order */
    Route::resource('orders', AdminOrderController::class)->names('orders');
    Route::post('orders/get-data', [AdminOrderController::class, 'getData'])->name('orders.getData');
    Route::post('orders/restore/{id}', [AdminOrderController::class, 'restore'])->name('orders.restore');
    Route::post('orders/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/check-email', [AdminOrderController::class, 'checkEmail'])->name('orders.check.email');
    Route::post('orders/check-phone', [AdminOrderController::class, 'checkPhone'])->name('orders.check.phone');

    /* Task */
    Route::resource('tasks', TaskController::class)->names('tasks');
    Route::post('tasks/get-data', [TaskController::class, 'getData'])->name('tasks.getData');
    Route::post('tasks/restore/{id}', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::post('tasks/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::post('tasks/check-email', [TaskController::class, 'checkEmail'])->name('tasks.check.email');
    Route::post('tasks/check-phone', [TaskController::class, 'checkPhone'])->name('tasks.check.phone');
    Route::post('tasks/order-ticket-listing', [TaskController::class, 'getOrderTickets'])->name('tasks.order.ticket.listing');
    Route::post('tasks/dev-user', [TaskController::class, 'getDevUser'])->name('tasks.dev.user');
    Route::post('tasks/assign-dev-user', [TaskController::class, 'assignDevUser'])->name('tasks.assign.dev.user');
    Route::post('tasks/get-service-variant', [TaskController::class, 'getServiceVariant'])->name('tasks.get.service.variant');

});


require __DIR__ . '/user-auth.php';

require __DIR__ . '/admin-auth.php';
