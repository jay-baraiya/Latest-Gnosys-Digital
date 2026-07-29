<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DigitalProductController;
use App\Http\Controllers\Admin\DigitalServiceController;
use App\Http\Controllers\Admin\EmailAccountController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\EventSeriesController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\BlogController as ForntBlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DigitalProductController as ProductController;
use App\Http\Controllers\DigitalServiceController as ServiceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController as UserProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/phpinfo', function () {
    return phpinfo();
});

Route::get('/admin', function () {
    return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('admin.login');
});

Route::get('/mail-champ', function () {
    return view('mail-champ.index');
});

Route::get('/account', [UserProfileController::class, 'edit'])->name('profile.edit');
Route::post('/account/update-user-info', [UserProfileController::class, 'updateUserInfo'])->name('profile.update.info');
Route::patch('/account', [UserProfileController::class, 'update'])->name('profile.update');
Route::delete('/account', [UserProfileController::class, 'destroy'])->name('profile.destroy');
Route::get('/account/get-states/{country_id}', [UserProfileController::class, 'getStates'])->name('profile.get.states');

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

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'check_is_agent_is_Admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('settings', SettingController::class)->names('settings');
    Route::post('settings/store-website-settings', [SettingController::class, 'storeWebsiteSetting'])->name('settings.storeWebsiteSetting');
    Route::post('settings/test-email', [SettingController::class, 'testEmail'])->name('settings.testEmail');

    /* users */
    Route::resource('users', UserController::class)->names('users');
    Route::post('users/get-data', [UserController::class, 'getData'])->name('users.getData');
    Route::post('users/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
    Route::post('users/update-status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::post('users/check-email', [UserController::class, 'checkEmail'])->name('users.check.email');
    Route::post('users/check-phone', [UserController::class, 'checkPhone'])->name('users.check.phone');
    Route::get('users/permission/{id}', [UserController::class, 'createPermission'])->name('users.permission.create');
    Route::post('users/permission/update/{id}', [UserController::class, 'updatePermission'])->name('users.permission.update');

    /* event_series */
    Route::resource('event-series', EventSeriesController::class)->names('event_series');
    Route::post('event-series/get-data', [EventSeriesController::class, 'getData'])->name('event_series.getData');
    Route::post('event-series/restore/{id}', [EventSeriesController::class, 'restore'])->name('event_series.restore');
    Route::post('event-series/update-status', [EventSeriesController::class, 'updateStatus'])->name('event_series.updateStatus');
    Route::post('event-series/check-name', [EventSeriesController::class, 'checkName'])->name('event_series.checkName');
    Route::post('event-series/check-slug', [EventSeriesController::class, 'checkSlug'])->name('event_series.checkSlug');

    /* events */
    Route::resource('event', EventController::class)->names('event');
    Route::post('event/get-data', [EventController::class, 'getData'])->name('event.getData');
    Route::post('event/check-title', [EventController::class, 'checkTitle'])->name('event.checkTitle');
    Route::post('event/check-slug', [EventController::class, 'checkSlug'])->name('event.checkSlug');

    Route::post('ajax/common/get-countries', [CommonController::class, 'getCountries'])->name('common.getCountries');
    Route::post('ajax/common/get-states', [CommonController::class, 'getStates'])->name('common.getStates');
    Route::post('ajax/common/get-cities', [CommonController::class, 'getCities'])->name('common.getCities');
    Route::post('ajax/custom-fields/get-field-type-data', [CommonController::class, 'getFieldTypeData'])->name('custom.fields.getFieldTypeData');
    Route::post('ajax/get-categories', [CommonController::class, 'getCategories'])->name('ajax.categories');
    Route::post('ajax/get-sub-categories', [CommonController::class, 'getSubCategories'])->name('ajax.subcategories');
    Route::post('ajax/common/get-services', [CommonController::class, 'getServices'])->name('common.getServices');

    Route::get('ajax/tickets/get-order-numbers', [CommonController::class, 'getOrderNumbers'])->name('tickets.get.order_numbers');
    Route::get('ajax/tickets/get-ticket-numbers', [CommonController::class, 'getTicketNumbers'])->name('tickets.get.ticket_numbers');

    Route::post('ajax/assign/get-data', [CommonController::class, 'getDevelopers'])->name('ajax.get.assign');
    Route::post('ajax/get-buyers', [CommonController::class, 'getBuyers'])->name('ajax.get.buyers');

    /* roles */
    Route::resource('roles', RoleController::class)->names('roles');
    Route::post('roles/get-data', [RoleController::class, 'getData'])->name('roles.getData');
    Route::post('roles/update-status', [RoleController::class, 'updateStatus'])->name('roles.updateStatus');
    Route::post('roles/check-role', [RoleController::class, 'checkRole'])->name('validate.role');

    /* departments */
    Route::resource('departments', DepartmentController::class)->names('departments');
    Route::post('departments/get-data', [DepartmentController::class, 'getData'])->name('departments.getData');
    Route::post('departments/restore/{id}', [DepartmentController::class, 'restore'])->name('departments.restore');
    Route::post('departments/update-status', [DepartmentController::class, 'updateStatus'])->name('departments.updateStatus');
    Route::post('departments/check-departments', [DepartmentController::class, 'checkDepartment'])->name('validate.departments');

    /* email accounts */
    Route::resource('email-accounts', EmailAccountController::class)->names('email_accounts');
    Route::post('email-accounts/get-data', [EmailAccountController::class, 'getData'])->name('email_accounts.getData');
    Route::post('email-accounts/restore/{id}', [EmailAccountController::class, 'restore'])->name('email_accounts.restore');
    Route::post('email-accounts/update-status', [EmailAccountController::class, 'updateStatus'])->name('email_accounts.updateStatus');
    Route::post('email-accounts/check-email-accounts', [EmailAccountController::class, 'checkEmailAccount'])->name('validate.email_accounts');

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
    Route::post('digital-products/check-name', [DigitalProductController::class, 'checkName'])->name('digital.products.check.name');
    Route::post('digital-products/check-sku', [DigitalProductController::class, 'checkSku'])->name('digital.products.check.sku');

    Route::resource('digital-services', DigitalServiceController::class)->names('digital.services');
    Route::post('digital-services/get-data', [DigitalServiceController::class, 'getData'])->name('digital.services.getData');
    Route::post('digital-services/restore/{id}', [DigitalServiceController::class, 'restore'])->name('digital.services.restore');
    Route::post('digital-services/update-status', [DigitalServiceController::class, 'updateStatus'])->name('digital.services.updateStatus');
    Route::post('digital-services/check-services-name', [DigitalServiceController::class, 'checkServiceName'])->name('digital.services.check.name');
    Route::post('digital-services/check-services-sku', [DigitalServiceController::class, 'checkServiceSku'])->name('digital.services.check.sku');

    Route::resource('coupons', CouponController::class)->names('coupons');
    Route::post('coupons/get-data', [CouponController::class, 'getData'])->name('coupons.getData');
    Route::post('coupons/update-status', [CouponController::class, 'updateStatus'])->name('coupons.updateStatus');
    Route::post('coupons/check-code', [CouponController::class, 'checkCode'])->name('coupons.check.code');

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
    Route::post('orders/order-ticket-listing', [AdminOrderController::class, 'getOrderTickets'])->name('orders.ticket.listing');
    Route::post('orders/dev-user', [AdminOrderController::class, 'getDevUser'])->name('orders.dev.user');
    Route::post('orders/user-billing-details', [AdminOrderController::class, 'getUserBillingDetails'])->name('orders.user.billing.details');

    /* Tickets */
    Route::post('tickets/fetch-emails', [TicketController::class, 'fetchEmails'])->name('tickets.fetch_emails');
    Route::resource('tickets', TicketController::class)->names('tickets');
    Route::post('tickets/get-data', [TicketController::class, 'getData'])->name('tickets.getData');
    Route::post('tickets/restore/{id}', [TicketController::class, 'restore'])->name('tickets.restore');
    Route::post('tickets/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    Route::get('tickets/{id}/generate-invoice', [TicketController::class, 'generateInvoice'])->name('tickets.generate_invoice');
    Route::post('tickets/{id}/generate-custom-invoice', [TicketController::class, 'generateCustomInvoice'])->name('tickets.generate_custom_invoice');
    Route::post('tickets/order-ticket-listing', [TicketController::class, 'getOrderTickets'])->name('tickets.order.ticket.listing');
    Route::post('tickets/dev-user', [TicketController::class, 'getDevUser'])->name('tickets.dev.user');
    Route::post('tickets/assign-dev-user', [TicketController::class, 'assignDevUser'])->name('tickets.assign.dev.user');
    Route::post('tickets/get-service-variant', [TicketController::class, 'getServiceVariant'])->name('tickets.get.service.variant');
    Route::post('/tickets/update-status', [TicketController::class, 'updateTicketStatus'])->name('tickets.update.status');
    Route::post('/tickets/get-item-qty', [TicketController::class, 'getItemQty'])->name('tickets.get-qty');

    Route::post('/tickets/store/task', [TicketController::class, 'storeTask'])->name('tickets.store.task');
    Route::post('/tickets/store/chat', [TicketController::class, 'storeChat'])->name('tickets.store.chat');
    Route::post('/tickets/get-chats', [TicketController::class, 'getChats'])->name('tickets.get.chats');
    Route::post('/tickets/delete-chat', [TicketController::class, 'deleteChat'])->name('tickets.delete.chat');
    Route::post('/tickets/update-chat-message', [TicketController::class, 'updateChatMessage'])->name('tickets.update.chat.message');
    Route::post('/tickets/store-user', [TicketController::class, 'storeUser'])->name('tickets.storeUser');
    Route::post('/tickets/{id}/store-reply', [TicketController::class, 'storeReply'])->name('tickets.storeReply');
    Route::post('/tickets/{id}/store-internal-note', [TicketController::class, 'storeInternalNote'])->name('tickets.storeInternalNote');

    // Ticket tasks
    Route::get('/tickets/{id}/tasks/create', [TicketController::class, 'createTask'])->name('tickets.tasks.create');
    Route::post('/tickets/{id}/tasks/store', [TicketController::class, 'storeTicketTask'])->name('tickets.tasks.store');
    Route::get('/tickets/{id}/tasks/{taskId}/edit', [TicketController::class, 'editTask'])->name('tickets.tasks.edit');
    Route::post('/tickets/{id}/tasks/{taskId}/update', [TicketController::class, 'updateTask'])->name('tickets.tasks.update');
    Route::post('/tickets/{id}/tasks/{taskId}/delete', [TicketController::class, 'deleteTask'])->name('tickets.tasks.delete');

    Route::resource('tasks', TaskController::class)->names('tasks');
    Route::post('tasks/get-data', [TaskController::class, 'getData'])->name('tasks.getData');
    Route::post('tasks/restore/{id}', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::post('tasks/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::post('tasks/check-email', [TaskController::class, 'checkEmail'])->name('tasks.check.email');
    Route::post('tasks/check-phone', [TaskController::class, 'checkPhone'])->name('tasks.check.phone');
    Route::post('tasks/{id}/store-reply', [TaskController::class, 'storeReply'])->name('tasks.storeReply');
    Route::post('tasks/{id}/store-internal-note', [TaskController::class, 'storeInternalNote'])->name('tasks.storeInternalNote');

    // Notifications
    Route::get('/notifications/dropdown', [NotificationController::class, 'dropdown'])->name('notifications.dropdown');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/test', [NotificationController::class, 'testNotification'])->name('notifications.test');

});

require __DIR__.'/payment-auth.php';

require __DIR__.'/user-auth.php';

require __DIR__.'/admin-auth.php';
