<?php

use App\Services\RssFeed;
use BotMan\BotMan\BotMan;
use App\Conversations\Onboarding;
use App\Middleware\ManagesUsersMiddleware;
use JoeDixon\BotManDrivers\Extensions\TemplateTemplate;

$botman = resolve('botman');
$middleware = new ManagesUsersMiddleware;
$botman->middleware->received($middleware);
$botman->middleware->matching($middleware);

$botman->hears('ARTICLES', function (BotMan $bot) {
    $rss = new RssFeed('https://medium.com/feed/@joedixon');
    $article = $rss->all()->last();
    $template = TemplateTemplate::create($article->getTitle());
    $template->addImage($article->getImage());
    $template->addUrl($article->getImage());
    $bot->reply($template);
});

$botman->fallback(function (BotMan $bot) {
    if ($bot->getMessage()->getExtras('is_new_user')) {
        return $bot->startConversation(new Onboarding);
    }
});
