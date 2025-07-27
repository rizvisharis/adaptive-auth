<?php

use App\Livewire\Register;
use App\Livewire\AdaptiveLogin;
use App\Livewire\OtpVerification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Livewire\SecurityQuestionVerification;

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

Route::get('/', AdaptiveLogin::class)->name('home');

Route::get('/registration', Register::class)->name('registration');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/api/start-typing-session', function () {
    $data = json_decode(request()->getContent(), true);
    $type = $data['type'] ?? null;
    $time = $data['time'] ?? null;

    if ($type === 'email') {
        session(['email_typing_start' => $time]);
    } elseif ($type === 'password') {
        session(['password_typing_start' => $time]);
    }

    return response()->json(['status' => 'ok']);
});

Route::get('/security-question', SecurityQuestionVerification::class)->name('security.question');
Route::get('/otp-verification', OtpVerification::class)->name('otp.verification');



require __DIR__ . '/auth.php';
