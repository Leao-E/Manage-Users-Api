<?php


namespace App\Models\AuxTables;


use App\Traits\Assets\DateUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RegKeys extends Model
{
    protected $primaryKey = "id";

    protected $table = 'hre_reg_key';

    protected $fillable = ['reg_key', 'name', 'hirer_id', 'dt_expire'];

    protected $casts = [ 'dt_expire' => 'datetime' ];

    public $timestamps = false;

    static public function removeExpired(){
        $now = DateUtil::now();
        return DB::table('hre_reg_key')->where('dt_expire', '<', $now)->delete();
    }

}
