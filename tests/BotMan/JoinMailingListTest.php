<?php

namespace Tests\BotMan;

use App\User;
use Newsletter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JoinMailingListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_signup_to_the_mailing_list()
    {
        factory(User::class)->create(['channel_id' => 12345678]);

        Newsletter::shouldReceive('subscribePending')
            ->once()
            ->with('test@justtesting.com');

        $this->bot->setUser(['id' => 12345678])
            ->receives('List')
            ->assertReply(trans('join_mailing_list.ask_for_email_address'))
            ->receives('test@justtesting.com')
            ->assertReply(trans('join_mailing_list.thank_you'));
    }

    /** @test */
    public function a_user_cannot_signup_to_the_mailing_list_with_an_invalid_email_address()
    {
        factory(User::class)->create(['channel_id' => 12345678]);

        Newsletter::shouldReceive('subscribePending')
            ->never();

        $this->bot->setUser(['id' => 12345678])
            ->receives('List')
            ->assertReply(trans('join_mailing_list.ask_for_email_address'))
            ->receives('testjusttesting.com')
            ->assertReply(trans('join_mailing_list.validate_email'));
    }
}
