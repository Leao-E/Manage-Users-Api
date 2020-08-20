<?php


namespace App\Models;

use App\Models\Pivots\HirerSystem;
use App\Models\Pivots\UserHirerSystems;
use App\Models\QueryProcessable\QueryProcessable;
use App\Traits\Models\UsesUUID;
use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
        $query = $this->hasManyThrough(
        Hirer::class,
        HirerSystem::class,
        'system_id',
        'id',
        'id',
        'hirer_id')
        ->select('hre_hirers.*', 'hre_hirer_systems.status', 'hre_hirer_systems.dt_expire')
        ->getQuery();

        $hirer = $query->getModel();

        $columns = $hirer->getFillable();

        foreach ($columns as $key => $column) {
            $columns[$key] = $hirer->getTable().'.'.$column;
        }

        $columns [ ] = 'hre_hirer_systems.status';
        $columns [ ] = 'hre_hirer_systems.dt_expire';

        return new QueryProcessable($query, $columns);
    }

    public function users()
    {
        $dbQuery = DB::table('usr_users')
            ->select('usr_users.*')->distinct()
            ->join('usr_user_hirer_systems', function ($join){
                $join->on('usr_users.id', '=', 'usr_user_hirer_systems.user_id');
            })
            ->join('hre_hirer_systems', function ($join){
                $join->on('hre_hirer_systems.id', '=', 'usr_user_hirer_systems.hirer_system_id');
            })
            ->where('usr_users.deleted_at', '=', null)
            ->where('hre_hirer_systems.deleted_at', '=', null)
            ->where('usr_user_hirer_systems.deleted_at', '=', null)
            ->where('hre_hirer_systems.system_id', '=', $this->id);

        $user = new User();

        $eloquentQuery = new EloquentQueryBuilder($dbQuery);

        $eloquentQuery->setModel($user);

        $columns = $user->getFillable();

        foreach ($columns as $key => $column) {
            $columns[$key] = $user->getTable().'.'.$column;
        }

        return new QueryProcessable($eloquentQuery, $columns);
    }

}
