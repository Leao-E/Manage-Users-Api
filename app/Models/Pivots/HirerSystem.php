<?php

namespace App\Models\Pivots;

use App\Exceptions\CustomExceptions\ApiException;
use App\Traits\Assets\DateUtil;
use App\Traits\Models\UsesUUID;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class HirerSystem extends Pivot
{
    use UsesUUID;

    protected $table = 'hre_hirer_systems';

    protected $fillable = ['hirer_id', 'system_id', 'dt_expire', 'status'];

    protected $casts = [ 'dt_expire' => 'datetime:d-m-Y' ];

    public $timestamps = false;

    /*
     * author: Emanuel F.G. Leão
     * resume: A função recebe os id's do sistema e do contratante
     * junto ao parametro opicional dt_expire, que representa
     */
    public function createFromRequest($hirer_id, $system_id, $dt_expire = null){
        $now = DateUtil::now();
        if ($dt_expire == null) {
            $dt_expire = $now->addMonth(1);
        }else{
            $dt_expire = explode('/', $dt_expire);
            $dt_expire = Carbon::create($dt_expire[2], $dt_expire[1], $dt_expire[0], 0,0,0);
        }

        if (DateUtil::isPast($dt_expire)){
            throw new ApiException('the field dt_expire must be a date after '.$now->format('d/m/Y'), '400');
        }

        $this->hirer_id = $hirer_id;
        $this->system_id = $system_id;
        $this->dt_expire = $dt_expire;
        $this->status = "ATIVO";
    }
}
