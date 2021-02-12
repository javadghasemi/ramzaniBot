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
}