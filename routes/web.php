<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    try {
        Mail::raw('تجرية إرسال إيميل من الموقع', function ($message) {
            $message->to('almutjul5011211@gmail.com')->subject('اختبار Laravel');
        });
        return "تم إرسال الإيميل بنجاح!";
    } catch (\Exception $e) {
        return "فشل الإرسال: " . $e->getMessage();
    }
});