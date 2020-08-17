<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomExceptions\ApiException;
use App\Models\Hirer;
use App\Traits\Assets\QueryParamsProcessor;
use App\Traits\Controllers\HirerController\HirerBroker;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use phpDocumentor\Reflection\Types\Object_;

class HirerController extends BaseController
{
    use QueryParamsProcessor, HirerBroker;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllHirers (Request $request)
    {
        /** @var Hirer $hirer */

        $hirer = new Hirer();
        $status = 200;

        $queryParams = $request->query();

        try {
            //processa os query params e retorna o response
            $response = $this->queryProcessor($hirer, $queryParams);
        } catch (ApiException $e) {
            $response = ['error' => $e->getMessage()];
            $status = $e->getStatus();
        }

        return response()->json($response, $status);
    }

    public function getHirer ($id)
    {
        $hirer = new Hirer();
        $status = 200;
        $response = new Object_();

        try {
            $response->data = $hirer->newQuery()->findOrFail($id);
        } catch (\Exception $e) {
            $status = 404;
            $response = ['error' => $e->getMessage()];
        }

        return response()->json($response, $status);
    }

    public function getSystems (Request $request, $id)
    {
        /** @var Hirer $hirer */
        $hirer = new Hirer();
        $status = 200;

        $query_params = $request->query();

        try {
            $hirer = $hirer->newQuery()->findOrFail($id);
            try {
                $response = $this->queryProcessor($hirer->systems(), $query_params);
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
        /** @var Hirer $hirer */
        $hirer = new Hirer();
        $status = 200;

        $query_params = $request->query();

        try {
            $hirer = $hirer->newQuery()->findOrFail($id);
            try {
                $response = $this->queryProcessor($hirer->users(), $query_params);
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

    public function getSelf ($id)
    {
        /** @var Hirer $hirer */

        $hirer = new Hirer();
        $status = 200;

        $response = new Object_();

        try {
            $hirer = $hirer->newQuery()->findOrFail($id);
            $response->data = $hirer->self()->firstOrFail();
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 404;
        }
        return response()->json($response, $status);
    }

    public function newHirer (Request $request)
    {
        $response = new Object_();
        $status = 200;

        try {
            $hirer = $this->create($request);
            try {
                $hirer->save();
                $response->data = $hirer;
            }catch (\Exception $exception){
                $response = ['error' => $exception->getMessage()];
                $status = 500;
            }
        }catch (ApiException $exception){
            $status = $exception->getStatus();
            $response = ['error' => $exception->getMessage()];
        }

        return response()->json($response, $status);
    }

    public function updateHirer (Request $request, $id)
    {
        /** @var Hirer $hirer */
        $hirer = new Hirer();
        $status = 200;
        $response = new Object_();

        try {
            $hirer = $hirer->newQuery()->findOrFail($id);
            try {
                $this->update($hirer, $request);
                $hirer->update();
                $response->data = $hirer;
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

    public function deleteHirer ($id)
    {
        /** @var Hirer $hirer */
        $hirer = new Hirer();
        $status = 200;

        try {
            $hirer = $hirer->findOrFail($id);
            $hirer->delete();
            $response = ['msg' => 'hirer deleted'];
        } catch (\Exception $e) {
            $status = 404;
            $response = ['error' => $e->getMessage()];
        }
        return response()->json($response, $status);
    }
}
