<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\Pivots\UserHirerSystems;
use App\Models\User;
use App\Traits\Assets\QueryParamsProcessor;
use App\Traits\Controllers\UserController\UserBroker;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use phpDocumentor\Reflection\Types\Object_;
use App\Traits\Controllers\UserController\HirerController;

class UserController extends BaseController
{
    use QueryParamsProcessor, UserBroker;

    public function getAllUsers(Request $request)
    {
        /** @var User $user */
        $user = new User();
        $status = 200;

        $queryParams = $request->query();

        try {
            $response = $this->queryProcessor($user, $queryParams);
        } catch (ApiException $e) {
            $response = ['error' => $e->getMessage()];
            $status = $e->getStatus();
        }

        return response()->json($response, $status);
    }

    public function getUser($id)
    {
        $user = new User();
        $status = 200;
        $response = new Object_();

        try {
            $response->data = $user->findOrFail($id);
        } catch (\Exception $e) {
            $status = 404;
            $response = ['error' => $e->getMessage()];
        }

        return response()->json($response, $status);
    }

    public function newUser(Request $request)
    {
        $response = new Object_();
        $status = 200;

        try {
            $user = $this->create($request);
            try {
                $user->save();
                $response->data = $user;
            } catch (\Exception $e) {
                $status = 500;
                $response = ['error' => $e->getMessage()];
            }
        } catch (ApiException $e) {
            $status = $e->getStatus();
            $response = ['error' => $e->getMessage()];
        }
        return response()->json($response, $status);
    }

    public function updateUser(Request $request, $id)
    {
        /** @var User $user */
        $user = new User();
        $status = 200;
        $response = new Object_();

        try {
            $user = $user->newQuery()->findOrFail($id);
            try {
                $this->update($user, $request);
                $user->update();
                $response->data = $user;
            } catch (\Exception $e) {
                if ($e instanceof ApiException){
                    $status = $e->getStatus();
                }else{
                    $status = 500;
                }
                $response = ['error' => $e->getMessage()];
            }
        } catch (\Exception $e) {
            $status = 404;
            $response = ['error' => $e->getMessage()];
        }

        return response()->json($response, $status);
    }

    public function deleteUser($id)
    {
        /** @var User $user */
        $user = new User();
        $status = 200;

        try {
            $user = $user->findOrFail($id);
            $user->delete();
            $response = ['msg' => 'user deleted'];
        } catch (\Exception $e) {
            $status = 404;
            $response = ['error' => $e->getMessage()];
        }
        return response()->json($response, $status);
    }

    public function getSystems(Request $request, $id)
    {
        /** @var User $user */
        $user = new User();
        $status = 200;
        $response = new Object_();

        try {
            $user = $user->findOrFail($id);
            $response->data = $user->systems()->get();

            $query_params = $request->query();

            if ($this->canPaginate($query_params)){
                try {
                    $response = $this->paginate($response->data, $query_params);
                }catch (\Exception $e){
                    $response = ['error' => $e->getMessage()];
                    $status = 400;
                }
            }
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 404;
        }

        return response()->json($response, $status);
    }

    public function associateSystem(Request $request, $id)
    {
        try {
            $user = User::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
        if(!$request->has('hirer_id') or !$request->has('system_id')){
            return response()->json(['error' => 'missing fields. try to send system id and hirer_id'], 400);
        }
        try {
            $userHirerSystem = new UserHirerSystems();

            $userHirerSystem->user_id = $user->id;
            $userHirerSystem->hirer_id = $request->hirer_id;
            $userHirerSystem->system_id = $request->system_id;

            $userHirerSystem->save();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['msg' => 'relations sucessfully created'], 201);
    }

    public function getHirers(Request $request, $id)
    {
        /** @var User $user */
        $user = new User();
        $status = 200;
        $response = new Object_();

        try {
            $user = $user->findOrFail($id);
            $response->data = $user->hirers()->get();

            $query_params = $request->query();

            if ($this->canPaginate($query_params)){
                try {
                    $response = $this->paginate($response->data, $query_params);
                }catch (\Exception $e) {
                    $response = ['error' => $e->getMessage()];
                    $status = 400;
                }
            }
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 404;
        }

        return response()->json($response, $status);
    }
}

