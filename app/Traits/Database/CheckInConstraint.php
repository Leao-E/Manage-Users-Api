<?php

namespace App\Traits\Database;

use \Illuminate\Support\Facades\DB;

trait CheckInConstraint {
    /*
     * author: Emanuel F.G. Leão
     * resume: a função recebe o nome da tabela, o nome da coluna e os valores
     * no qual queremos adicionar à coluna da tabela
     * uma constraint 'CHECK ( IN ( 'valor_1', 'valor_2' ) ),
     * onde os valores serão passados por meio de um array
     */
    function addCheckIn (string $table, string $collumn, array $values){
        $query = "ALTER TABLE ".$table." ADD CONSTRAINT ".$collumn."_check_in_val CHECK ( ".$collumn." IN (";

        $count = 1;
        $array_len = count($values);

        foreach ($values as $value){
            $query .= "'".$value."'";

            if ($count != $array_len){
                $query .= ", ";
            }

            $count++;
        }
        $query .= "))";

        DB::statement($query);
    }
}
