<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\AuxTables\RegKeys;
use App\Models\Pivots\UserHirerSystems;
use App\Models\QueryProcessable\QueryProcessable;
use App\Models\User;
use App\Traits\Assets\QueryParamsProcessor;
use App\Traits\Controllers\UserController\UserBroker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Support\Carbon;
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

        /** @var Builder $query */

        $query = User::all()->toQuery();

        $columns = $user->getFillable();

        $queryProcessable = new QueryProcessable($query, $columns);

        try {
            $response = $this->queryProcessor($queryProcessable, $queryParams);
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

    public function registerUser(Request $request)
    {
        $this->validate($request, [
            "unq_nick"=> "required|string|unique:usr_users",
            "email" => "required|email|unique:usr_users",
            "usr_type" => "required|string|in:PESSOA_FISICA, PESSOA_JURIDICA",
            "name" => "required|string",
            "dt_birth" => "required|string",
            "cpf" => "required_if:usr_type,==,PESSOA_FISICA|unique:usr_users|string",
            "cnpj" => "required_if:usr_type,==,PESSOA_JURIDICA|unique:usr_users|string",
            "password" => "required|string",
            'reg_key' => "required|string|exists:hre_reg_key"
        ]);

        try {
            $dtBirth = Carbon::createFromFormat('d/m/Y', $request->dt_birth);
        } catch (\Exception $e) {
            return response(['error' => 'invalid dt_birth format. use dd/mm/yyyy'], 400);
        }

        $response = new Object_();
        $status = 200;
        $user = new User();

        if (!RegKeys::isValidKey($request->reg_key)){
            return response()->json(['error' => 'expired reg_key'], 400);
        }

        try {
            $user->name = $request->name;
            $user->unq_nick = $request->unq_nick;
            $user->email = $request->email;
            $user->usr_type = $request->usr_type;
            $user->dt_birth = Carbon::createFromFormat('d/m/Y', $request->dt_birth);
            if (isset($request->cpf)){
                $user->cpf = $request->cpf;
            }
            if (isset($request->cpf)){
                $user->cpf = $request->cpf;
            }
            $user->password = $request->password;

            $user->save();

            $regKey = RegKeys::query()->findOrFail($request->reg_key);

            $userHirerSystem = new UserHirerSystems([
                'user_id' => $user->id,
                'hirer_id' => $regKey->hirer_id,
                'system_id' => $regKey->system_id
            ]);

            $userHirerSystem->save();

            $response = $user;

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
        $status = 200;

        $query_params = $request->query();

        try {
            /** @var User $user */
            $user = User::query()->findOrFail($id);
            try {
                $response = $this->queryProcessor($user->systems(), $query_params);
            }catch (ApiException $e){
                $response = ['error' => $e->getMessage()];
                $status = $e->getStatus();
            }
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 404;
        }
        /**@var Collection $e */

        return response()->json($response, $status);
    }

    public function getHirers(Request $request, $id)
    {
        $status = 200;

        $query_params = $request->query();

        try {
            $user = User::query()->findOrFail($id);

            try {
                $response = $this->queryProcessor($user->hirers(), $query_params);
            }catch (\Exception $e) {
                $response = ['error' => $e->getMessage()];
                $status = 400;
            }

        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 404;
        }

        return response()->json($response, $status);
    }
}

