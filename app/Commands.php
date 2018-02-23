<?php

namespace App;

use BotMan\BotMan\BotMan;
use JoeDixon\BotManDrivers\Extensions\MultiTemplate;
use JoeDixon\BotManDrivers\Extensions\TemplateTemplate;
use JoeDixon\BotManDrivers\Extensions\ButtonTemplate;

class Commands
{
    public function latestArticles(BotMan $bot)
    {
        $articles = Article::orderBy('published_at', 'desc')->limit(10)->get();

        if ($articles->count() === 0) {
            return $bot->reply(trans('commands.no_articles'));
        }

        $multiTemplate = MultiTemplate::create();
        foreach ($articles as $article) {
            if (!$article->image_url) {
                continue;
            }

            // titles and subtitles are limited to 80 characters with Facebook.
            // Ensure 77 characters, plus 3 for the elipsis
            $template = TemplateTemplate::create(str_limit($article->title, 77));
            $template->addSubtitle(str_limit($article->description, 77));
            $template->addUrl($article->url);
            $template->addImage($article->image_url);
            $template->addButton(ButtonTemplate::create('Read more')->addType('url')->addAction($article->url));
            $multiTemplate->addTemplate($template);
        }

        $bot->reply(trans('commands.latest_articles'));
        sleep(1);
        $bot->reply($multiTemplate);
    }
}
