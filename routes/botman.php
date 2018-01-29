<?php

use BotMan\BotMan\BotMan;
use App\Conversations\Onboarding;
use App\Conversations\JoinMailingList;
use App\Middleware\ManagesUsersMiddleware;

$botman = resolve('botman');
$middleware = new ManagesUsersMiddleware;
$botman->middleware->received($middleware);
$botman->middleware->matching($middleware);

$botman->hears('articles', 'App\Commands@latestArticles');

$botman->hears('list', function (BotMan $bot) {
    $bot->startConversation(new JoinMailingList);
});

$botman->fallback(function (BotMan $bot) {
    if ($bot->getMessage()->getExtras('is_new_user')) {
        return $bot->startConversation(new Onboarding);
    }
});
