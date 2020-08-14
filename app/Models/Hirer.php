<?php


namespace App\Models;

use App\Models\AuxTables\RegKeys;
use App\Models\Pivots\HirerSystem;
use App\Models\Pivots\UserHirerSystems;
use App\Traits\Models\UsesUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function regKeys()
    {
        return $this->hasMany(RegKeys::class, 'hirer_id', 'id');
    }

    static public function newRegKey()
    {
        $key = Str::random(4).'-'.Str::random(4).'-'.Str::random(2);
    }
}
