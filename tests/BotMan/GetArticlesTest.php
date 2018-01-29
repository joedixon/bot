<?php

namespace Tests\BotMan;

use App\User;
use App\Article;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JoeDixon\BotManDrivers\Extensions\MultiTemplate;

class GetArticlesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_user_can_get_a_list_of_articles()
    {
        factory(Article::class, 5)->create();
        factory(User::class)->create(['channel_id' => 12345678]);

        $this->bot->setUser(['id' => 12345678])
            ->receives('Articles')
            ->assertReply(trans('commands.latest_articles'))
            ->assertTemplate(MultiTemplate::class);
    }

    /** @test */
    public function a_user_gets_notified_if_there_are_no_articles()
    {
        factory(User::class)->create(['channel_id' => 12345678]);

        $this->bot->setUser(['id' => 12345678])
            ->receives('Articles')
            ->assertReply(trans('commands.no_articles'));
    }
}
