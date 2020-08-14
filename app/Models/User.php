<?php


namespace App\Models;

use App\Models\Pivots\UserHirerSystems;
use App\Traits\Assets\DateUtil;
use App\Traits\Models\UsesUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Query\Builder;
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
        'dt_birth' => 'datetime:d-m-Y'
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
        return $this->hasManyThrough(Hirer::class, UserHirerSystems::class, 'user_id', 'id', 'id', 'hirer_id');
    }

    public function systems()
    {


//        dd($this->hasManyThrough(
//            System::class,
//            UserHirerSystems::class,
//            'user_id',
//            'id',
//            'id',
//            'system_id'
//        )->toSql());

        return $this->hasManyThrough(
            System::class,
            UserHirerSystems::class,
            'user_id',
            'id',
            'id',
            'system_id'
        )->whereExists(function ($query){
            $now = DateUtil::now();
            /** @var Builder $query */
            $query->select('hre_hirer_systems.system_id')
                ->whereRaw('`hre_hirer_systems`.`system_id` = `sys_systems`.`id`')
                ->from('hre_hirer_systems')
                ->where('hre_hirer_systems.dt_expire', '>', $now);
        });
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
