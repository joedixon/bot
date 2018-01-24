<?php

use BotMan\BotMan\BotMan;
use App\Conversations\Onboarding;
use App\Middleware\ManagesUsersMiddleware;

$botman = resolve('botman');
$middleware = new ManagesUsersMiddleware;
$botman->middleware->received($middleware);
$botman->middleware->matching($middleware);

$botman->fallback(function (BotMan $bot) {
    if ($bot->getMessage()->getExtras('is_new_user')) {
        return $bot->startConversation(new Onboarding);
    }
});
