<?php

namespace App\Console\Commands;

use App\Article;
use App\Services\RssFeed;
use Illuminate\Console\Command;

class ParseRssFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:parse-rss-feeds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse an RSS feed into the database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach(config('bot.rss_feed_urls') as $feed)
        {
            $feed = new RssFeed($feed);
            foreach ($feed->all() as $article) {
                $newArticle = Article::firstOrCreate(['unique_id' => $article->getId()]);
                $newArticle->update([
                    'title' => $article->getTitle(),
                    'description' => $article->getDescription(),
                    'url' => $article->getUrl(),
                    'image_url' => $article->hasImage() ? $article->getImage() : null,
                    'published_at' => $article->getDate(),
                ]);
            }
        }
    }
}
