<?php

namespace App\Middleware;

use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class ManagesUsersMiddleware implements Received, Matching
{
    /**
     * Handle an incoming message.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        $user = User::firstOrCreate([
            'channel_id' => $message->getSender(),
        ]);

        $message->addExtras('is_new_user', $user->wasRecentlyCreated);

        $user->update([
            'first_name' => $bot->getUser()->getFirstName(),
            'last_name' => $bot->getUser()->getLastName()
        ]);
    }

    /**
     * @param IncomingMessage $message
     * @param string $pattern
     * @param bool $regexMatched Indicator if the regular expression was matched too
     * @return bool
     */
    public function matching(IncomingMessage $message, $pattern, $regexMatched)
    {
        // All messages only match, when not a new user.
        // This allows us to direct them through the
        // onboarding experience
        return $regexMatched && $message->getExtras('is_new_user') === true;
    }
}
