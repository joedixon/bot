<?php

use BotMan\BotMan\BotMan;
use App\Conversations\Onboarding;
use BotMan\BotMan\Middleware\ApiAi;
use App\Conversations\JoinMailingList;
use App\Middleware\ManagesUsersMiddleware;

$botman = resolve('botman');
$usersMiddleware = new ManagesUsersMiddleware;
$botman->middleware->received($usersMiddleware);
$botman->middleware->matching($usersMiddleware);
$botman->middleware->received(ApiAi::create(config('bot.dialog_flow_token')));

$botman->hears('articles', 'App\Commands@latestArticles');

$botman->hears('list', function (BotMan $bot) {
    $bot->startConversation(new JoinMailingList);
});

$botman->fallback(function (BotMan $bot) {
    // if the user is new, fire the onboarding sequence
    if ($bot->getMessage()->getExtras('is_new_user')) {
        return $bot->startConversation(new Onboarding);
    }

    // delegate to DialogFlow to determine response
    return $bot->reply($bot->getMessage()->getExtras('apiReply'));
});
