<?php

use App\Middleware\ManagesUsersMiddleware;

$botman = resolve('botman');
$middleware = new ManagesUsersMiddleware;
$botman->middleware->received($middleware);
$botman->middleware->matching($middleware);

$botman->fallback(function ($bot) {
    if ($bot->getMessage()->getExtras('is_new_user')) {
        return $bot->reply('You\'re a new user, welcome!');
    }

    $bot->reply('You\'re old news I\'m afraid!');
});
