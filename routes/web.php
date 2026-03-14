<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Marketing\DashboardController as MarketingDashboard;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\UserController;
use App\Exports\KonsumenExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KonsumenImport;

/*
|--------------------------------------------------------------------------
| HALAMAN LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Auth::routes([
    'register' => false,
    'reset' => false,
]);

/*
|--------------------------------------------------------------------------
| REGISTER (jika ingin admin buat user)
|--------------------------------------------------------------------------
*/

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| SEMUA ROUTE WAJIB LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | REDIRECT SETELAH LOGIN
    |--------------------------------------------------------------------------
    */

    Route::get('/home', function () {

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'marketing') {
            return redirect()->route('marketing.dashboard');
        }

        abort(403);

    })->name('home');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/marketing/dashboard', [MarketingDashboard::class, 'index'])
        ->middleware('role:marketing,admin')
        ->name('marketing.dashboard');


    /*
    |--------------------------------------------------------------------------
    | LIVE SEARCH
    |--------------------------------------------------------------------------
    */

    Route::get('/konsumen/live-search', [KonsumenController::class, 'liveSearch'])
        ->name('konsumen.liveSearch');

    Route::get('/followups/live-search', [FollowUpController::class, 'liveSearch'])
        ->name('followups.liveSearch');


    /*
    |--------------------------------------------------------------------------
    | FOLLOW UP KHUSUS MARKETING
    |--------------------------------------------------------------------------
    */

    Route::get('/marketing/followups/today', [FollowUpController::class, 'today'])
        ->name('marketing.followups.today');


    /*
    |--------------------------------------------------------------------------
    | RESOURCE ROUTES
    |--------------------------------------------------------------------------
    */

    Route::resource('konsumen', KonsumenController::class)
    ->parameters(['konsumen' => 'konsumen'])
    ->except(['show']);

    Route::resource('followups', FollowUpController::class)->except(['show']);

    Route::resource('produk', ProdukController::class);

    Route::resource('targets', TargetController::class);

    Route::resource('transaksi', TransaksiController::class);


    /*
    |--------------------------------------------------------------------------
    | USERS (HANYA ADMIN)
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::resource('users', UserController::class);

    });


    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    Route::get('/export-konsumen', function () {

        $status = request('status');

        return Excel::download(
            new KonsumenExport($status),
            'konsumen.xlsx'
        );

    })->name('konsumen.export');

    /*
    |--------------------------------------------------------------------------
    | IMPORT EXCEL
    |--------------------------------------------------------------------------
    */
    Route::post('/import-konsumen', [KonsumenController::class, 'import'])
        ->name('konsumen.import');

    /*
    |--------------------------------------------------------------------------
    | AJAX DASHBOARD MARKETING
    |--------------------------------------------------------------------------
    */

    Route::get('/marketing/followups-today', [MarketingDashboard::class, 'followupsToday'])
        ->name('marketing.followupsToday');


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', function () {

        Auth::logout();

        return redirect('/');

    })->name('logout');

});


