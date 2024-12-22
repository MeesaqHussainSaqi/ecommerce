<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        return response()->json($this->user->all());
    }

    public function show($id)
    {
        return response()->json($this->user->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'email', 'password']);
        $data['password'] = bcrypt($data['password']); // Hash the password
        return response()->json($this->user->create($data));
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
