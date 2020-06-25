<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MoodleUser extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'mdl_user';
}