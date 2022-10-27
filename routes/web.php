<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\UserController;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\BookingController;

use App\Models\UserType;
use App\Models\CafeTableStatus;
use App\Models\BookingStatus;

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::resource('user', UserController::class)->names([
        'index'     => 'user.index',
        'create'    => 'user.create',
        'store'     => 'user.store',
        'show'      => 'user.show',
        'edit'      => 'user.edit',
        'update'    => 'user.update',
        'destroy'   => 'user.destroy'
    ]);

    Route::get('/dashboard/user', function () {
        return Inertia\Inertia::render('User/Dashboard', [
            'user_index_route' => route('user.index'),
            'user_types' => UserType::where('id', '!=', 1)->get()
        ]);
    })->name('dashboard');

    Route::get('cafe/readqr/{id}', function($id) {
        return Inertia\Inertia::render('Cafe/ReadQR', [
            'cafe_id' => $id,
            'booking_index_route' => route('booking.index')
        ]);
    })->name('cafe.readqr');

    Route::resource('cafe', CafeController::class)->names([
        'index'     => 'cafe.index',
        'create'    => 'cafe.create',
        'store'     => 'cafe.store',
        'show'      => 'cafe.show',
        'edit'      => 'cafe.edit',
        'update'    => 'cafe.update',
        'destroy'   => 'cafe.destroy'
    ]);

    Route::get('/dashboard/cafe', function () {
        return Inertia\Inertia::render('Cafe/Dashboard', [
            'user' => Auth::user(),
            'cafe_table_statusses' => CafeTableStatus::all(),
            'cafe_index_route' => route('cafe.index'),
            'booking_index_route' => route('booking.index'),
            'booking_dashboard_route' => route('booking.dashboard')
        ]);
    })->name('cafe.dashboard');

    Route::get('/cancel/waiting/{id}', [BookingController::class, 'cancel_waiting'])->name('cancel.waiting');
    Route::resource('booking', BookingController::class)->names([
        'index'     => 'booking.index',
        'create'    => 'booking.create',
        'store'     => 'booking.store',
        'show'      => 'booking.show',
        'edit'      => 'booking.edit',
        'update'    => 'booking.update',
        'destroy'   => 'booking.destroy'
    ]);

    Route::get('/dashboard/booking', function () {
        return Inertia\Inertia::render('Booking/Dashboard', [
            'booking_index_route' => route('booking.index'),
            'booking_statusses' => BookingStatus::all()
        ]);
    })->name('booking.dashboard');

    Route::get('/waiting/cancel/{id}', function ($id) {
        return Inertia\Inertia::render('Booking/Cancel', [
            'cafe_id' => $id,
            'cancel_waiting_route' => route('cancel.waiting', $id)
        ]);
    })->name('waiting.cancel');
});
