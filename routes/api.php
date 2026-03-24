<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\PropertyStatusController;
use App\Http\Controllers\Admin\FinishingTypeController;
use App\Http\Controllers\Public\PublicPropertyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Admin API - Real Estate Backend
| Version 1.0
| 
| هذه الـ APIs خاصة بلوحة تحكم المدير لنظام إدارة العقارات
|
*/

// ===========================================
// المسارات العامة (بدون توثيق) - Public API
// ===========================================

Route::prefix('public')->name('public.')->group(function () {
    
    // 🟢 جلب العقارات مع الفلترة
    Route::get('/properties', [PublicPropertyController::class, 'index'])
        ->name('properties.index');
    
    // 🟢 جلب تفاصيل عقار معين
    Route::get('/properties/{id}', [PublicPropertyController::class, 'show'])
        ->name('properties.show');
    
    // 🟢 جلب أنواع العقارات
    Route::get('/property-types', [PropertyTypeController::class, 'index'])
        ->name('property-types.index');
    
    // 🟢 جلب الميزات
    Route::get('/features', [FeatureController::class, 'index'])
        ->name('features.index');
});

// ===========================================
// مسارات تسجيل الدخول (بدون توثيق)
// ===========================================
Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login')
    ->middleware('throttle:10,1'); // تحديد 10 محاولات في الدقيقة


// ===========================================
// المسارات المحمية (تتطلب توثيق)
// ===========================================
Route::prefix('admin')
    ->name('admin.')
    ->middleware([
        'auth:sanctum',      // التحقق من التوكن
        'role:admin,editor', // التحقق من الدور (admin أو editor)
        'throttle:60,1'      // تحديد 60 طلب في الدقيقة
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | 1. إدارة الأنواع الأساسية (Master Tables)
        |--------------------------------------------------------------------------
        | هذه المسارات خاصة بإدارة أنواع العقارات، الحالات، التشطيب، والميزات
        | متاحة لكل من Admin و Editor
        */

        // ===========================================
        // 1.1 أنواع العقارات (Property Types)
        // ===========================================
        Route::prefix('property-types')->name('property-types.')->group(function () {
            Route::post('/', [PropertyTypeController::class, 'store'])          ->name('store');
            Route::get('/', [PropertyTypeController::class, 'index'])           ->name('index');
            Route::get('/{id}', [PropertyTypeController::class, 'show'])        ->name('show');
            Route::put('/{id}', [PropertyTypeController::class, 'update'])      ->name('update');
            Route::delete('/{id}', [PropertyTypeController::class, 'destroy'])  ->name('destroy');
        });

        // ===========================================
        // 1.2 حالات العقارات (Property Statuses)
        // ===========================================
        Route::prefix('property-statuses')->name('property-statuses.')->group(function () {
            Route::post('/', [PropertyStatusController::class, 'store'])           ->name('store');
            Route::get('/', [PropertyStatusController::class, 'index'])            ->name('index');
            Route::get('/default', [PropertyStatusController::class, 'getDefault'])->name('default');
            Route::get('/{id}', [PropertyStatusController::class, 'show'])         ->name('show');
            Route::put('/{id}', [PropertyStatusController::class, 'update'])       ->name('update');
            Route::delete('/{id}', [PropertyStatusController::class, 'destroy'])   ->name('destroy');
        });

        // ===========================================
        // 1.3 أنواع التشطيب (Finishing Types)
        // ===========================================
        Route::prefix('finishing-types')->name('finishing-types.')->group(function () {
            Route::post('/', [FinishingTypeController::class, 'store'])           ->name('store');
            Route::get('/', [FinishingTypeController::class, 'index'])            ->name('index');
            Route::get('/default', [FinishingTypeController::class, 'getDefault'])->name('default');
            Route::get('/{id}', [FinishingTypeController::class, 'show'])         ->name('show');
            Route::put('/{id}', [FinishingTypeController::class, 'update'])       ->name('update');
            Route::delete('/{id}', [FinishingTypeController::class, 'destroy'])   ->name('destroy');
        });

        // ===========================================
        // 1.4 الميزات (Features)
        // ===========================================
        Route::prefix('features')->name('features.')->group(function () {
            Route::post('/', [FeatureController::class, 'store'])              ->name('store');
            Route::get('/', [FeatureController::class, 'index'])               ->name('index');
            Route::get('/groups', [FeatureController::class, 'groups'])        ->name('groups');
            Route::get('/{id}', [FeatureController::class, 'show'])            ->name('show');
            Route::put('/{id}', [FeatureController::class, 'update'])          ->name('update');
            Route::delete('/{id}', [FeatureController::class, 'destroy'])      ->name('destroy');
            Route::post('/{id}/restore', [FeatureController::class, 'restore'])->name('restore');
        });

        /*
        |--------------------------------------------------------------------------
        | 2. إدارة العقارات (Properties Management)
        |--------------------------------------------------------------------------
        | المسارات الخاصة بإدارة العقارات بالكامل
        | متاحة لكل من Admin و Editor
        */

        Route::prefix('properties')->name('properties.')->group(function () {

            // ===========================================
            // 2.1 العمليات الأساسية (CRUD)
            // ===========================================
            Route::post('/', [PropertyController::class, 'store'])                ->name('store');   // إنشاء عقار
            Route::get('/', [PropertyController::class, 'index'])                 ->name('index');   // عرض العقارات مع فلترة
            Route::get('/{id}', [PropertyController::class, 'show'])              ->name('show');    // عرض عقار محدد
            Route::put('/{id}', [PropertyController::class, 'update'])            ->name('update');  // تحديث عقار
            Route::delete('/{id}', [PropertyController::class, 'destroy'])        ->name('destroy'); // حذف ناعم
            Route::post('/{id}/restore', [PropertyController::class, 'restore'])  ->name('restore'); // استعادة عقار محذوف

            // ===========================================
            // 2.2 العلاقات (Relations)
            // ===========================================
            Route::post('/{propertyId}/features', [PropertyController::class, 'attachFeatures'])
                ->name('features.attach'); // ربط ميزات بالعقار

            // ===========================================
            // 2.3 إدارة الوسائط (Media Management)
            // ===========================================
            Route::post('/{propertyId}/images/bulk', [PropertyController::class, 'addMultipleImages'])
                ->name('images.bulk'); // إضافة صور متعددة
            
            Route::post('/{propertyId}/videos', [PropertyController::class, 'addPropertyVideo'])
                ->name('videos.store'); // إضافة فيديو
        });

        /*
        |--------------------------------------------------------------------------
        | 3. مسارات خاصة بالـ Admin فقط (Admin Only)
        |--------------------------------------------------------------------------
        | هذه المسارات متاحة فقط للمدير (Admin)
        | سيتم إضافتها لاحقاً مع نظام الأدوار والصلاحيات المتكامل
        */
        
        Route::middleware(['role:admin'])->prefix('admin-only')->name('admin.')->group(function () {
            // سيتم إضافة مسارات خاصة بالمدير فقط هنا
            // مثل: إدارة المستخدمين، التقارير، إعدادات النظام، إلخ
        });
    });


/*
|--------------------------------------------------------------------------
| مسارات إضافية (Optional Routes)
|--------------------------------------------------------------------------
| هذه المسارات للاستخدامات المستقبلية
*/

// Route::fallback(function () {
//     return response()->json([
//         'message' => 'Route not found',
//         'status' => 404
//     ], 404);
// });