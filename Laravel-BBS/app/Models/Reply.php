<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['topic_id', 'user_id', 'content'];
}
