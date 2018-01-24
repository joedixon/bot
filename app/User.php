<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel_id', 'first_name', 'last_name', 'wants_notifications',
    ];

    public function turnOnNotifications()
    {
        $this->update(['wants_notifications' => true]);
    }
}
