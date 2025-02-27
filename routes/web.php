<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AttendanceCDontroller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HolidayController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// bonvinant-love of food
//voracious- avid for something,f
Route::get("/", [UserController::class, 'login'])->name('home');
Route::get("/login", [UserController::class, 'login'])->name('login');

Route::get("/admin/login", [AdminController::class, 'signin'])->name('admin.login');
Route::post("signin_post", [AdminController::class, 'signin_post'])->name('signin.post');


Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
    Route::get("dashboard", [AdminController::class, 'dashboard'])->name("admin.dashboard");
    Route::get("profile", [AdminController::class, 'profile'])->name("admin.profile");
    Route::post("profile_post", [AdminController::class, 'profile_post'])->name("admin.profile_post");

    Route::get("change_password", [AdminController::class, 'change_password'])->name("admin.change_password");
    Route::post("change_password_post", [AdminController::class, 'change_password_post'])->name("admin.change_password_post");

    Route::resource("users", UserController::class, ['as' => 'admin']);
    Route::get("users/attendance/{id}", [UserController::class, 'userAttendance'])->name("admin.userAttendance");

    // Fix: Add 'as' => 'admin.' to holidays resource  
    Route::resource("holidayss", HolidayController::class, ['as' => 'admin']);

    Route::get('/download-logs', [AdminController::class, 'downloadLogs'])->name('download.logs');
    Route::post('/users/import', [UserController::class, 'import'])->name('admin.import');
    Route::get("logout", [AdminController::class, 'logout'])->name("admin.logout");
});



Route::prefix('user')->as("user.")->group(function () {
    Route::get('/', [UserController::class, 'login'])->name('login'); // Route for storing attendance
    Route::post('/login', [UserController::class, 'login_post'])->name('login_post'); // Route for storing attendance

    Route::middleware("auth")->group(function () {
        Route::get("dashboard", [UserController::class, 'dashboard'])->name("dashboard");
        Route::get("attendance", [UserController::class, 'attendance'])->name('attendance');

        Route::get("help", [UserController::class, 'help'])->name('help');


        Route::post('/attendance/store', [AjaxController::class, 'storeAttendance'])->name('attendance.store');

        // Route for updating attendance (Check-out)
        Route::post('/attendance/update', [AjaxController::class, 'updateAttendance'])->name('attendance.update');
        Route::get('/attendance/fetch', [AjaxController::class, 'fetchAttendance'])->name('attendance.fetch');
        


        Route::get('/attendance/fetch/today', [AjaxController::class, 'fetchAttendancetoday'])->name('attendance.fetch.today');

        //
        Route::get("attendance_records", [UserController::class, 'attendance_records'])->name("attendance_records");
        Route::get("holidays", [UserController::class, 'holidays'])->name("holidays");
        Route::get("profile", [UserController::class, 'profile'])->name("profile");

        Route::get("emp-directory", [UserController::class, 'directory'])->name("directory");
        Route::get("all-emp-directory", [AjaxController::class, 'Employeedirectory'])->name("employee.directory");

        Route::post('/change-password', [UserController::class, 'changePassword'])->name('change.password');
        Route::get("logout", [UserController::class, 'logout'])->name("logout");

    });
});





// Ajax Routes for image upload

Route::post("image-upload", [AjaxController::class, "uploadImage"])->name('image-upload');
Route::post("image-delete", [AjaxController::class, "deleteImage"])->name('image-delete');
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return "Cache is cleared";
});


// Route::get('/help', function () {
//     return view('users.help');
// })->name('users.help');
