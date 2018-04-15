<?php

namespace Tests\BotMan;

use App\User;
use Tests\TestCase;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JoeDixon\BotManDrivers\Extensions\ActionTemplate;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_new_user_will_trigger_the_onboarding_sequence()
    {
        $this->bot->setUser(['id' => 12345678])
            ->receives('Hi')
            ->assertReply(trans('onboarding.welcome', ['name' => config('app.name')]))
            ->assertTemplate(Question::class)
            ->receives('Yes')
            ->assertTemplate(ActionTemplate::class);

        $this->assertDatabaseHas('users', ['channel_id' => 12345678]);
    }

    /** @test */
    public function a_new_user_who_wants_to_stay_updated_should_be_flagged_as_such()
    {
        $this->bot->setUser(['id' => 12345678])
            ->receives('Hi')
            ->assertReply(trans('onboarding.welcome', ['name' => config('app.name')]))
            ->assertTemplate(Question::class)
            ->receives('Yes')
            ->assertTemplate(ActionTemplate::class);

        $this->assertDatabaseHas('users', ['channel_id' => 12345678, 'wants_notifications' => true]);
    }

    /** @test */
    public function a_new_user_who_does_not_want_to_stay_updated_should_be_flagged_as_such()
    {
        $this->bot->setUser(['id' => 12345678])
            ->receives('Hi')
            ->assertReply(trans('onboarding.welcome', ['name' => config('app.name')]))
            ->assertTemplate(Question::class)
            ->receives('No')
            ->assertTemplate(ActionTemplate::class);

        $this->assertDatabaseHas('users', ['channel_id' => 12345678, 'wants_notifications' => false]);
    }

    /** @test */
    public function an_existing_user_should_not_trigger_the_onboarding_sequence()
    {
        factory(User::class)->create(['channel_id' => 12345678]);

        $this->bot->setUser(['id' => 12345678])
            ->receives('Hi')
            ->assertReplyIsNot(trans('onboarding.welcome', ['name' => config('app.name')]));
    }
}
