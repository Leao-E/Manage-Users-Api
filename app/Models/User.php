<?php


namespace App\Models;

use App\Models\Pivots\UserHirerSystems;
use App\Models\QueryProcessable\QueryProcessable;
use App\Traits\Assets\DateUtil;
use App\Traits\Models\UsesUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use SoftDeletes, UsesUUID, Authenticatable, Authorizable;

    protected $guarded = ['id'];

    protected $table = 'usr_users';

    protected $fillable = [
        'unq_nick', 'email', 'usr_type',
        'name', 'cpf', 'cnpj', 'dt_birth',
        'is_sudo', 'is_hirer', 'password'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'is_sudo' => 'boolean',
        'is_hirer' => 'boolean',
        'dt_birth' => 'datetime:d/m/Y'
    ];

    protected $hidden = ['password'];

    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = Hash::make($pass);
    }

    static public function clearOldConfirmLoginKeys(){
        DB::table('confirm_login')->where('expire', '<', DateUtil::now())->delete();
    }

    public function hirers()
    {
        $hirer = new Hirer();

        $dbQuery = DB::table('hre_hirers')
            ->select('hre_hirers.*')->distinct()
            ->join('hre_hirer_systems', function ($joins){
                $joins->on('hre_hirers.id', '=', 'hre_hirer_systems.hirer_id');
            })
            ->join('usr_user_hirer_systems', function ($joins) {
                $joins->on('hre_hirer_systems.id', '=', 'usr_user_hirer_systems.hirer_system_id');
            })
            ->where('hre_hirers.deleted_at', '=', null)
            ->where('hre_hirer_systems.deleted_at', '=', null)
            ->where('usr_user_hirer_systems.deleted_at', '=', null)
            ->where('usr_user_hirer_systems.user_id', $this->id);

        $eloquentQuery = new EloquentQueryBuilder($dbQuery);

        $eloquentQuery->setModel($hirer);

        $columns = $hirer->getAttributes();

        return new QueryProcessable($eloquentQuery, $columns);
        //return $this->hasManyThrough(Hirer::class, UserHirerSystems::class, 'user_id', 'id', 'id', 'hirer_id');
    }

    public function systems()
    {
        $system = new System();

        $dbQuery = DB::table('sys_systems')
            ->select([
                'sys_systems.*', 'hre_hirer_systems.status', 'hre_hirer_systems.hirer_id'
            ])
            ->join('hre_hirer_systems', function ($joins) {
                $joins->on('hre_hirer_systems.system_id', '=', 'sys_systems.id');
            })
            ->join('usr_user_hirer_systems', function ($joins) {
                $joins->on('hre_hirer_systems.id', '=', 'usr_user_hirer_systems.hirer_system_id');
            })
            ->where('sys_systems.deleted_at', '=', null)
            ->where('hre_hirer_systems.deleted_at', '=', null)
            ->where('usr_user_hirer_systems.deleted_at', '=', null)
            ->where('usr_user_hirer_systems.user_id', $this->id);


        $eloquentQuery = new EloquentQueryBuilder($dbQuery);
        $eloquentQuery->setModel($system);

        $columns = $system->getFillable();

        foreach ($columns as $key => $column) {
            $columns[$key] = $system->getTable().'.'.$column;
        }

        $columns [] = 'hre_hirer_systems.status';

        return new QueryProcessable($eloquentQuery, $columns);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    }
