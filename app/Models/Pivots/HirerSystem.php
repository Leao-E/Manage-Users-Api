<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class HirerSystem extends Pivot
{

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
        if ($dt_expire == null) {
            $dt_expire = new \DateTime('now');
            $dt_expire->modify('+1 month');
        }else{
            $dt_expire = \DateTime::createFromFormat('d', $dt_expire)
                ->modify('+1 month');
        }

        $this->hirer_id = $hirer_id;
        $this->system_id = $system_id;
        $this->dt_expire = $dt_expire;
        $this->status = "ATIVO";
    }
}
