<?php

namespace App\Models\Pivots;

use \Illuminate\Database\Eloquent\Relations\Pivot;

class UserHirerSystems extends Pivot
{
    protected $table = 'usr_user_hirer_systems';

    protected $fillable = ['user_id', 'hirer_id', 'system_id'];

    public $timestamps = false;

}
