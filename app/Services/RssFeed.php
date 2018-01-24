<?php

namespace App\Services;

use SimplePie;

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

    public function getTitle()
    {
        return $this->item->get_title();
    }

    public function getContent()
    {
        return $this->item->get_content();
    }

    public function getImage()
    {
        return str_before(str_after($this->getContent(), 'src="'), '"');
    }
}
