<?php
namespace App\Repositories;

use App\Repositories\BaseInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Utilities\Utilities;
use App\Configurations\Constants;
use App\Response\BaseRepositoryResponse;
use Exception;

abstract class BaseRepository implements BaseInterface
{
    protected $model;

    // public function __construct(Model $model)
    // {
    //     $this->model = $model;
    // }
    // public function __construct()
    // {
    //     $this->model = $this->model();
    // }
    // abstract protected function model();
    public function all(Request $request)
    {
        try {
            $data = $this->model->all();
            $code = Constants::HTTP_OK;
            $data_result = new BaseRepositoryResponse();
            $data_result->setValues($data->toArray());
            // $data_result->setPermissions($this->permissions);
            // $data_result->setParameters($parameters);

            $response = Utilities::BuildSuccessResponse(Constants::Success, $code,"List.", $data_result);
            return response()->json($response, $code);
        } catch (Exception $e) {
            return catchErrorResponse($e);
        }   
    }

    public function find($id)
    {
        try{
            $rec = $this->model->findOrFail($id);
            $code = Constants::HTTP_CREATED;
            $response = Utilities::BuildSuccessResponse(
                Constants::Success,
                $code,
                "Record found.",   
                $rec->toArray()
            );
            return response()->json($response, $code);
        }
        catch (Exception $e) {
            return catchErrorResponse($e);
        }
    }

    public function create($attributes)
    {
        try{
            $createdRec = $this->model->create($attributes);
            $code = Constants::HTTP_CREATED;
            $response = Utilities::BuildSuccessResponse(
                Constants::Success,
                $code,
                "Successfully created.",
                $createdRec->toArray()
            );
            return response()->json($response, $code);
        }
        catch (Exception $e) {
            return catchErrorResponse($e);
        }
    }

    public function update($id, array $attributes)
    {
        $model = $this->find($id);
        $model->update($attributes);
        return $model;
    }

    public function delete($id)
    {
        $model = $this->find($id);
        $model->delete();
        return true;
    }
}
