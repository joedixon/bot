<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = [];

    protected $dates = ['published_at'];

    public function scopeForSending($query)
    {
        return $query->where('has_been_sent', false)->whereNotNull('image_url');
    }

    public static function markAsSent()
    {
        static::where('has_been_sent', false)->update(['has_been_sent' => true]);
    }
}
