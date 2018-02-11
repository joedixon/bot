<?php

namespace App\Jobs;

use App\User;
use App\Article;
use BotMan\BotMan\Facades\BotMan;
use JoeDixon\BotManDrivers\UbisendDriver;
use JoeDixon\BotManDrivers\Extensions\MultiTemplate;
use JoeDixon\BotManDrivers\Extensions\TemplateTemplate;

class SendNewArticles
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendMessagesToUsers($this->buildMessages());

        Article::markAsSent();
    }

    private function buildMessages()
    {
        $messages = [];

        Article::forSending()->chunk(10, function ($articles) use (&$messages) {
            foreach ($articles as $article) {
                $message = MultiTemplate::create();
                foreach ($articles as $article) {
                    // titles and subtitles are limited to 80 characters with Facebook.
                    // Ensure 77 characters, plus 3 for the elipsis
                    $template = TemplateTemplate::create(str_limit($article->title, 77));
                    $template->addSubtitle(str_limit($article->description, 77));
                    $template->addUrl($article->url);
                    $template->addImage($article->image_url);
                    $message->addTemplate($template);
                }
            }

            $messages[] = $message;
        });

        return $messages;
    }

    private function sendMessagesToUsers(array $messages)
    {
        if (empty($messages)) {
            return;
        }

        foreach (User::wantsUpdates()->get() as $user) {
            foreach ($messages as $message) {
                BotMan::say($message, $user->channel_id, UbisendDriver::class);
            }
        }
    }
}
