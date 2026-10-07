<?php

use App\Http\Controllers\Auth\MagicLinkController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Livewire\Pages\Auth\ConfirmPassword;
use App\Livewire\Pages\Auth\ForgotPassword;
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\LoginOtp;
use App\Livewire\Pages\Auth\MagicLink;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\Auth\ResetPassword;
use App\Livewire\Pages\Auth\VerifyEmail;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->middleware('guest')->name('login');

Route::get('/otp-login', LoginOtp::class)
    ->middleware('guest')
    ->name('otp.login');

Route::get('/login/magic', MagicLink::class)
    ->middleware('guest')
    ->name('login.magic');

Route::get('/auth/magic/{email}', [MagicLinkController::class, 'verify'])
    ->middleware('guest')
    ->name('auth.magic.verify');
Route::get('/register', Register::class)->middleware('guest')->name('register');
Route::get('/forgot-password', ForgotPassword::class)->middleware('guest')->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->middleware('guest')->name('password.reset');
Route::get('/verify-email', VerifyEmail::class)->middleware('auth')->name('verification.notice');
Route::get('/confirm-password', ConfirmPassword::class)->middleware('auth')->name('password.confirm');

Route::get('/auth/{driver}/redirect', [SocialLoginController::class, 'redirect'])
    ->middleware('guest')
    ->name('social.redirect');

Route::get('/auth/{driver}/callback', [SocialLoginController::class, 'callback'])
    ->middleware('guest')
    ->name('social.callback');
