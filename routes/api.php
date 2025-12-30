<?php

use Illuminate\Http\Request;
use App\Http\Controllers\XenditCallback;
use Illuminate\Support\Facades\Route;

Route::post('xendit/callback/disbursement', [XenditCallback::class, 'handle']);
Route::post('xendit/callback/payout', [XenditCallback::class, 'handlePayout']);