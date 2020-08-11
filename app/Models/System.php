<?php


namespace App\Models;

use App\Models\Pivots\HirerSystem;
use App\Models\Pivots\UserHirerSystems;
use App\Traits\Models\UsesUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class System extends Model
{
    use SoftDeletes, UsesUUID;

    protected $guarded = ['id'];

    protected $table = 'sys_systems';

    protected $fillable = [
        'name', 'storg_size', 'storg_path',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function hirers()
    {
        return $this->hasManyThrough(Hirer::class, HirerSystem::class, 'system_id', 'id', 'id', 'hirer_id');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, UserHirerSystems::class, 'system_id', 'id', 'id', 'user_id');
    }

}
