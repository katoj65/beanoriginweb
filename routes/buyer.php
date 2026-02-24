<?php
use Inertia\Inertia;





Route::middleware(['auth', 'role:buyer'])->group(function () {












Route::get('/cooperative/notifications', function () {
    return Inertia::render('CooperativeNotificationPage');
})->name('cooperative.notifications');








});
