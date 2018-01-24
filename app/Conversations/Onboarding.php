<?php

namespace App\Conversations;

use App\User;
use BotMan\BotMan\Messages\Incoming\Answer;
use JoeDixon\BotManDrivers\Extensions\ActionTemplate;
use JoeDixon\BotManDrivers\Extensions\ButtonTemplate;
use JoeDixon\BotManDrivers\Extensions\SurveyTemplate;
use BotMan\BotMan\Messages\Conversations\Conversation;

class Onboarding extends Conversation
{
    /**
     * Start the conversation
     */
    public function run()
    {
        $this->provideGreeting();
    }

    public function provideGreeting()
    {
        $this->bot->reply(trans('onboarding.welcome', ['name' => config('app.name')]));

        $this->askToStayUpdated();
    }

    public function askToStayUpdated()
    {
        $survey = SurveyTemplate::create(trans('onboarding.stay_updated', ['name' => config('app.name')]));
        $survey->addResponses(['Yes', 'No']);

        $this->ask($survey, function (Answer $answer) {
            if ($answer->getText() === 'Yes') {
                $this->turnOnNotifications($answer);
                return $this->setExpectations(trans('onboarding.stay_updated_yes'));
            } else {
                return $this->setExpectations(trans('onboarding.stay_updated_no'));
            }
        });
    }

    public function setExpectations($message)
    {
        $action = ActionTemplate::create($message . "\n\n" . trans('onboarding.expectations', ['name' => config('app.name')]));
        $action->addButton(ButtonTemplate::create('Latest Articles')->addType('trigger')->addAction('ARTICLES'));
        $action->addButton(ButtonTemplate::create('Join the list')->addType('trigger')->addAction('LIST'));

        $this->bot->reply($action);
    }

    public function turnOnNotifications(Answer $answer)
    {
        $sender = $answer->getMessage()->getSender();
        $user = User::where('channel_id', $sender)->firstOrFail();

        $user->turnOnNotifications();
    }
}
