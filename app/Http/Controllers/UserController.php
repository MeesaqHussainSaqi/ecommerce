<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\User\UserInterface;
use App\Utilities\Utilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Configurations\Constants;
use Exception;

class UserController extends Controller
{
    private $user;
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
        $this->model = new User();
    }

    public function index()
    {
        // return response()->json($this->user->all());
    }
    public function all(Request $request)
    {
        return $this->user->all($request);
    }

    public function show($id)
    {
        return $this->user->find($id);
    }

    public function store(Request $request)
    {
        try{
            $validationResponse = $this->validateRequest($request);
            if ($validationResponse !== null) {
                return $validationResponse;
            }
            return $this->user->create($request->all());
        } catch (Exception $e) {
            return catchErrorResponse($e);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'email']);
        return response()->json($this->user->update($id, $data));
    }

    public function destroy($id)
    {
        return response()->json(['success' => $this->user->delete($id)]);
    }
}
