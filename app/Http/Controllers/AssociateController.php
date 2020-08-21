<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\Pivots\HirerSystem;
use App\Models\Pivots\UserHirerSystems;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AssociateController extends BaseController
{
    public function createUserHirerSystem(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'hirer_id' => 'required',
            'system_id' => 'required',
        ]);
        try {
            $userHirerSystem = new UserHirerSystems();
            $hirerSystem = HirerSystem::query()
                ->where('hirer_id', $request->hirer_id)
                ->where('system_id', $request->system_id)
                ->firstOrFail();

            $userHirerSystem->user_id = $request->user_id;
            $userHirerSystem->hirer_system_id = $hirerSystem->id;

            $userHirerSystem->save();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['msg' => 'relations sucessfully created'], 201);
    }

    public function createHirerSystem(Request $request)
    {
        $this->validate($request, [
            'hirer_id' => 'required',
            'system_id' => 'required',
            'dt_expire' => 'required',
        ]);

        try {
            $hirerSystem = new HirerSystem();

            $hirerSystem->createFromRequest($request->hirer_id, $request->system_id, $request->dt_expire);

            $hirerSystem->save();
        } catch (\Exception $e) {
            if ($e instanceof ApiException){
                return response()->json(['error' => $e->getMessage()], $e->getStatus());
            }else{
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        return response()->json(['msg' => 'relations sucessfully created'], 201);
    }

    public function removeUserHirerSystem(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'hirer_id' => 'required',
            'system_id' => 'required',
        ]);
        try {
            $hirerSystem = HirerSystem::query()
                ->where('hirer_id', $request->hirer_id)
                ->where('system_id', $request->system_id)
                ->firstOrFail();
            $userHirerSystem = UserHirerSystems::query()->where('user_id', $request->user_id)
                ->where('hirer_system_id', $hirerSystem->id)
                ->firstOrFail();

            $userHirerSystem->delete();

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['msg' => 'relations sucessfully created'], 200);
    }

    public function removeHirerSystem(Request $request)
    {
        $this->validate($request, [
            'hirer_id' => 'required',
            'system_id' => 'required',
        ]);

        try {
            $hirerSystem = HirerSystem::query()->where('hirer_id', $request->hirer_id)
                ->where('system_id',$request->system_id)->firstOrFail();

            $hirerSystem->status = 'INATIVO';

            $hirerSystem->update();

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['msg' => 'relations sucessfully created'], 201);
    }
}
