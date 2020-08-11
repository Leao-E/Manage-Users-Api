<?php


namespace App\Models;

use App\Models\Pivots\HirerSystem;
use App\Models\Pivots\UserHirerSystems;
use App\Traits\Models\UsesUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Hirer extends Model
{
    use SoftDeletes, UsesUUID;

    protected $guarded = ['id'];

    protected $table = 'hre_hirers';

    protected $fillable = ['name', 'hirer_type', 'user_id'];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function self()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function systems()
    {
        return $this->hasManyThrough(System::class, HirerSystem::class, 'hirer_id', 'id', 'id', 'system_id');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, UserHirerSystems::class, 'hirer_id', 'id', 'id', 'user_id');
    }

    public function regKeys(array $options)
    {

    }

    public function newRegKey(\DateTime $expire)
    {

    }
}
