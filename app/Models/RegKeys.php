<?php


namespace App\Models\AuxTables;


use App\Models\System;
use App\Traits\Assets\DateUtil;
use App\Traits\Models\UsesUUID;
use \Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegKeys extends Model
{
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model){
            $model->reg_key = Str::uuid()->toString();
        });
    }

    protected $primaryKey = "reg_key";

    protected $table = 'hre_reg_key';

    protected $fillable = ['name', 'hirer_id', 'system_id', 'dt_expire'];

    protected $casts = [ 'dt_expire' => 'datetime' ];

    public $timestamps = false;

    static public function removeExpired(){
        $now = DateUtil::now();
        return DB::table('hre_reg_key')->where('dt_expire', '<', $now)->delete();
    }

    static public function isValidKey($regKey){
        $dtExpire = Carbon::create(
            DB::table('hre_reg_key')->select('dt_expire')
                ->where('reg_key', $regKey)->value('dt_expire')
        );
        if (DateUtil::isFuture($dtExpire)){
            return true;
        }
        return false;
    }

    static public function getKeySystem ($regKey){
        try {
            $system = System::query()->findOrFail(
                DB::table('hre_reg_key')->select('system_id')
                    ->where('reg_key', $regKey)->value('system_id')
            );
        } catch (\Exception $e) {
            throw $e;
        }

        return $system;
    }

}
