# Deploy your own chatbot

## Clone the repository

`git clone git@github.com:joedixon/bot.git`

## Install dependencies

`composer install`

## Setup environment

Firstly, copy the example environment file

`cp .env.example .env`

Now update the environment variables.

Finally, set an application key

`php artisan key:generate`

## Migrate the database

`php artisan migrate`

## Set your RSS feeds

Open up `config/bot.php` and add as many RSS feed URLs to the `rss_feed_urls` key

## Import articles

`php artisan php artisan bot:parse-rss-feeds --sent`

*Note:* adding the `--sent` switch here will ensure all the articles imported are flagged as having already been sent. This will prevent all articles being blasted to your users who request to stay updated during the onboarding sequence.

Now you're good to go! 