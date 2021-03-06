<?php

namespace Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class User extends Eloquent
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'username', 'lastname', 'chat_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function times(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Time::class);
    }
}