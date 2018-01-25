<?php

namespace App\Services;

use SimplePie;
use Carbon\Carbon;

class RssFeed
{
    private $feed;

    private $items;

    public function __construct($url)
    {
        $this->feed = new SimplePie;
        $this->feed->set_feed_url($url);
        $this->feed->set_cache_location(storage_path('framework/cache'));
        $this->feed->init();
        $this->getItems();
    }

    public function getItems()
    {
        $this->items = collect();
        foreach ($this->feed->get_items() as $item) {
            $this->items->push(new RssFeedItem($item));
        }
    }

    public function all()
    {
        return $this->items;
    }

    public function first()
    {
        return $this->items->first();
    }

    public function getTitle()
    {
        return $this->feed->get_title();
    }
}

class RssFeedItem
{
    private $item;

    public function __construct(\SimplePie_Item $item)
    {
        $this->item = $item;
    }

    public function getId()
    {
        return $this->item->get_id();
    }

    public function getUrl()
    {
        return $this->item->get_permalink();
    }

    public function getTitle()
    {
        return $this->item->get_title();
    }

    public function getDescription()
    {
        return strip_tags($this->item->get_description());
    }

    public function getContent()
    {
        return $this->item->get_content();
    }

    public function hasImage()
    {
        return $this->getImage() === '' ? false : true;
    }

    public function getImage()
    {
        return preg_match('/(https?:\/\/.*\.(?:png|jpg))/i', $this->item->get_content(), $matches) === 1 ? $matches[0] : false;
    }

    public function getTags()
    {
        return $this->item->get_item_tags();
    }

    public function getDate()
    {
        return new Carbon($this->item->get_date());
    }
}
