<?php


namespace App\Models;

use App\Models\AuxTables\RegKeys;
use App\Models\Pivots\HirerSystem;
use App\Models\QueryProcessable\QueryProcessable;
use App\Traits\Models\UsesUUID;
use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Hirer extends Model
{
    use SoftDeletes, UsesUUID;

    protected $guarded = ['id'];

    protected $table = 'hre_hirers';

    protected $fillable = ['name', 'hirer_type', 'user_id', 'cnpj'];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function self()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function systems()
    {
        $system = new System();

        $query = $this->hasManyThrough(System::class, HirerSystem::class, 'hirer_id', 'id', 'id', 'system_id')
            ->select(['sys_systems.*', 'hre_hirer_systems.status'])->getQuery();

        $columns = $system->getFillable();

        foreach ($columns as $key => $column) {
            $columns[$key] = $system->getTable().'.'.$column;
        }

        $columns [] = 'hre_hirer_systems.status';

        return new QueryProcessable($query, $columns);
    }

    public function users()
    {
        $dbQuery = DB::table('usr_users')->select('usr_users.*')->distinct()
            ->join('usr_user_hirer_systems', function ($joins){
                $joins->on('usr_user_hirer_systems.user_id', '=', 'usr_users.id');
            })
            ->join('hre_hirer_systems', function ($joins){
                $joins->on('usr_user_hirer_systems.hirer_system_id', '=', 'hre_hirer_systems.id');
            })
            ->where('usr_users.deleted_at','=',null)
            ->where('hre_hirer_systems.deleted_at','=',null)
            ->where('usr_user_hirer_systems.deleted_at','=',null)
            ->where('hre_hirer_systems.hirer_id', $this->id);

        $user = new User();

        $eloquentQuery = new EloquentQueryBuilder($dbQuery);

        $eloquentQuery->setModel($user);

        $columns = $user->getFillable();

        foreach ($columns as $key => $column) {
            $columns[$key] = $user->getTable().'.'.$column;
        }

        return new QueryProcessable($eloquentQuery, $columns);
    }

    public function regKeys()
    {
        return $this->hasMany(RegKeys::class, 'hirer_id', 'id');
    }

}
