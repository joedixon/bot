<?php

namespace App;

use BotMan\BotMan\BotMan;
use JoeDixon\BotManDrivers\Extensions\MultiTemplate;
use JoeDixon\BotManDrivers\Extensions\TemplateTemplate;

class Commands
{
    public function latestArticles(BotMan $bot)
    {
        $articles = Article::orderBy('published_at', 'desc')->limit(10)->get();
        $multiTemplate = MultiTemplate::create();
        foreach ($articles as $article) {
            if (!$article->image_url) {
                continue;
            }

            $template = TemplateTemplate::create($article->title);
            $template->addSubtitle(str_limit($article->description, 77));
            $template->addUrl($article->url);
            $template->addImage($article->image_url);
            $multiTemplate->addTemplate($template);
        }

        $bot->reply(trans('commands.latest_articles'));
        $bot->reply($multiTemplate);
    }
}
