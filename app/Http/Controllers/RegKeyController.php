<?php

namespace App\Http\Controllers;

use App\Models\AuxTables\RegKeys;
use App\Models\Hirer;
use App\Traits\Assets\DateUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Controller as BaseController;

class RegKeyController extends BaseController
{
    public function createRegKey(Request $request)
    {
        $this->validate($request, [
            'hirer_id' => 'required',
            'name' => 'required',
            'durationDays' => 'required',
        ]);

        if ($request->durationDays > 30){
            return response()->json(['error' => 'max duration expired'], 400);
        }

        $now = DateUtil::now();

        $expire = $now->addDays($request->durationDays);

        $key = Str::random(4).'-'.Str::random(4).'-'.Str::random(2);

        $regKey = new RegKeys([
            'reg_key' => Hirer::newRegKey(),
            'name' => $request->name,
            'hirer_id' => $request->hirer_id,
            'dt_expire' => $expire
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
