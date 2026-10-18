<?php

use App\Domains\Interactions\Http\Controllers\Admin\InteractionController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/interactions/admins', [InteractionController::class, 'admins'])->name('interactions.admins');
    Route::post('/interactions/admins', [InteractionController::class, 'storeAdmin'])->name('interactions.admins.store');
    Route::put('/interactions/admins/{user}', [InteractionController::class, 'updateAdmin'])->name('interactions.admins.update');
    Route::post('/interactions/admins/{user}/block', [InteractionController::class, 'toggleBlockAdmin'])->name('interactions.admins.block');
    Route::delete('/interactions/admins/{user}', [InteractionController::class, 'destroyAdmin'])->name('interactions.admins.destroy');
    Route::get('/interactions/access-levels', [InteractionController::class, 'accessLevels'])->name('interactions.access-levels');
    Route::post('/interactions/access-levels', [InteractionController::class, 'storeAccessLevel'])->name('interactions.access-levels.store');
    Route::put('/interactions/access-levels/{level}', [InteractionController::class, 'updateAccessLevel'])->name('interactions.access-levels.update');
    Route::post('/interactions/access-levels/{level}/block', [InteractionController::class, 'toggleBlockAccessLevel'])->name('interactions.access-levels.block');
    Route::delete('/interactions/access-levels/{level}', [InteractionController::class, 'destroyAccessLevel'])->name('interactions.access-levels.destroy');
    Route::get('/interactions/comments', [InteractionController::class, 'comments'])->name('interactions.comments');
    Route::post('/interactions/comments/{comment}/approve', [InteractionController::class, 'approveComment'])->name('interactions.comments.approve');
    Route::delete('/interactions/comments/{comment}', [InteractionController::class, 'destroyComment'])->name('interactions.comments.destroy');
    Route::get('/interactions/visits', [InteractionController::class, 'visits'])->name('interactions.visits');
    Route::get('/interactions/polls', [InteractionController::class, 'polls'])->name('interactions.polls');
    Route::post('/interactions/polls', [InteractionController::class, 'storePoll'])->name('interactions.polls.store');
    Route::delete('/interactions/polls/{poll}', [InteractionController::class, 'destroyPoll'])->name('interactions.polls.destroy');
    Route::get('/interactions/tickets', [InteractionController::class, 'tickets'])->name('interactions.tickets');
    Route::post('/interactions/tickets/{ticket}/reply', [InteractionController::class, 'replyTicket'])->name('interactions.tickets.reply');
    Route::post('/interactions/tickets/{ticket}/close', [InteractionController::class, 'closeTicket'])->name('interactions.tickets.close');
    Route::get('/interactions/profile', [InteractionController::class, 'editProfile'])->name('interactions.profile');
    Route::post('/interactions/profile', [InteractionController::class, 'updateProfile'])->name('interactions.profile.save');
});
