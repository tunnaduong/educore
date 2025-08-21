<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel cho chat giữa 2 người dùng
Broadcast::channel('chat-user-{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Public channel cho chat trong lớp học (chỉ thành viên lớp mới được join)
Broadcast::channel('chat-class-{classId}', function ($user, $classId) {
    return $user->classrooms()->where('classrooms.id', $classId)->exists();
});
