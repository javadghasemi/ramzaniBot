<?php

namespace Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class Lesson extends Eloquent
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
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