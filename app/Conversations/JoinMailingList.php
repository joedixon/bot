<?php

namespace App\Conversations;

use Newsletter;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class JoinMailingList extends Conversation
{
    public function run()
    {
        return $this->askForEmailAddress();
    }

    public function askForEmailAddress()
    {
        $this->ask(trans('join_mailing_list.ask_for_email_address'), function (Answer $answer) {
            if (filter_var($answer->getText(), FILTER_VALIDATE_EMAIL) !== false) {
                Newsletter::subscribePending($answer->getText());

                return $this->bot->reply(trans('join_mailing_list.thank_you'));
            }

            return $this->repeat(trans('join_mailing_list.validate_email'));
        });
    }
}
