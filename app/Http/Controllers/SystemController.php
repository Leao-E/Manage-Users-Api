<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\Pivots\HirerSystem;
use App\Models\Pivots\UserHirerSystems;
use App\Models\System;
use App\Traits\Assets\QueryParamsProcessor;
use App\Traits\Controllers\SystemController\SystemBroker;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use phpDocumentor\Reflection\Types\Object_;

class SystemController extends BaseController
{
    use QueryParamsProcessor, SystemBroker;

    public function getAllSystems (Request $request)
    {
        /** @var System $system */
        $system = new System();
        $status = 200;

        $queryParams = $request->query();

        try {
            //processa os query params e retorna o response
            $response = $this->queryProcessor($system, $queryParams);
        } catch (ApiException $e) {
            $response = ['error' => $e->getMessage()];
            $status = $e->getStatus();
        }

        return response()->json($response, $status);
    }

    public function getSystem ($id)
    {
        /** @var System $system */
        $system = new System();
        $status = 200;
        $response = new Object_();

        try {
            $response->data = $system->newQuery()->findOrFail($id);
        } catch (\Exception $e) {
            $status = 404;
            $response = ['error' => $e->getMessage()];
        }

        return response()->json($response, $status);
    }

    public function getHirers (Request $request, $id)
    {
        /** @var System $system */
        $system = new System();
        $status = 200;

        $query_params = $request->query();

        try {
            $system = $system->newQuery()->findOrFail($id);
            try {
                $response = $this->queryProcessor($system->hirers(), $query_params);
            } catch (ApiException $e) {
                $response = ['error' => $e->getMessage()];
                $status = $e->getStatus();
            }
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 404;
        }

        return response()->json($response, $status);
    }

    public function getUsers (Request $request, $id)
    {
        /** @var System $system */
        $system = new System();
        $status = 200;

        $query_params = $request->query();

        try {
            $system = $system->newQuery()->findOrFail($id);
            try {
                $response = $this->queryProcessor($system->users(), $query_params);
            } catch (ApiException $e) {
                $response = ['error' => $e->getMessage()];
                $status = $e->getStatus();
            }
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 404;
        }

        return response()->json($response, $status);
    }

    public function newSystem (Request $request)
    {
        $response = new Object_();
        $status = 200;

        try {
            $system = $this->create($request);
            try {
                $system->save();
                $response->data = $system;
            }catch (\Exception $e){
                $response = ['erro' => $e->getMessage()];
                $status = 500;
            }
        }catch (ApiException $e){
            $status = $e->getStatus();
            $response = ['error' => $e->getMessage()];
        }

        return response()->json($response, $status);
    }

    public function updateSystem (Request $request, $id)
    {
        $status = 200;
        $response = new Object_();

        try {
            /** @var System $system */
            $system = System::query()->findOrFail($id);
            try {
                $this->update($system, $request);
                $system->update();
                $response->data = $system;
            } catch (\Exception $e) {
                if ($e instanceof ApiException){
                    $status = $e->getStatus();
                }else{
                    $status = 500;
                }
                $response = ['error' => $e->getMessage()];
            }
        } catch (\Exception $e){
            $status = 404;
            $response = ['error' => $e->getMessage()];
        }

        return response()->json($response, $status);
    }

    public function associateHirer(Request $request, $id)
    {
        try {
            $system = System::query()->findOrFail($id);
        } catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
        if (!$request->has('hirer_id')){
            return response()->json(['error' => 'missing fields. try to send hirer_id and dt_expire (optional)'], 400);
        }
        try {
            $hirerSystem = new HirerSystem();

            $hirerSystem->createFromRequest($request->hirer_id, $system->id, $request->dt_expire);

            $hirerSystem->save();
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['msg' => 'relations sucessfully created'], 201);
    }

    public function associateUser(Request $request, $id)
    {
        try {
            $system = System::query()->findOrFail($id);
        } catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
        if (!$request->has('hirer_id')){
            return response()->json(['error' => 'missing fields. try to send hirer_id and dt_expire (optional)'], 400);
        }
        try {
            $userHirerSystem = new UserHirerSystems();

            $userHirerSystem->user_id = $request->user_id;
            $userHirerSystem->hirer_id = $request->hirer_id;
            $userHirerSystem->system_id = $system->id;

            $userHirerSystem->save();;
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['msg' => 'relations sucessfully created'], 201);
    }

    public function deleteSystem ($id)
    {
        /** @var System $system */
        $system = new System();
        $status = 200;

        try {
            $system = $system->newQuery()->findOrFail($id);
            $system->delete();
            $response = ['msg' => 'system deleted'];
        } catch (\Exception $e) {
            $status = 404;
            $response = ['error' => $e->getMessage()];
        }
        return response()->json($response, $status);
    }
}