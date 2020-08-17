<?php

namespace App\Http\Controllers;

use App\Models\AuxTables\RegKeys;
use App\Models\Hirer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class RegKeyController extends BaseController
{
    public function createRegKey(Request $request)
    {
        $this->validate($request, [
            'hirer_id' => 'required',
            'system_id' => 'required',
            'name' => 'required',
            'dt_expire' => 'required',
        ]);

        $dateArray = explode('/', $request->dt_expire);

        $year = $dateArray[2];
        $month = $dateArray[1];
        $day = $dateArray[0];

        $dt_expire = Carbon::create($year, $month, $day, 0, 0, 0);

        $regKey = new RegKeys([
            'name' => $request->name,
            'hirer_id' => $request->hirer_id,
            'system_id' => $request->system_id,
            'dt_expire' => $dt_expire
        ]);

        try {
            $regKey->save();
        } catch (\Exception$e){
            return response()->json(['error' => 'verify data integrity'], 400);
        }

        return response()->json(['message' => 'reg key sucessful created'], 201);
    }

    public function getRegKeys($id)
    {
        try {
            $hirer = Hirer::query()->findOrFail($id);
        } catch (\Exception $e){
            return response()->json(['error' => 'hirer not found'], 404);
        }

        return response()->json([
            "data" => $hirer->regKeys()->get(),
        ]);
    }

    public function deleteRegKey(Request $request, $id)
    {
        try {
            $regKey = RegKeys::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'register key does not exists'], 404);
        }
        $regKey->delete();
        return response()->json(['message' => 'register key sucessful deleted'], 200);
    }
}
