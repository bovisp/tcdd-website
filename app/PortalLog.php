<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortalLog extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'mdl_logstore_standard_log';
}
