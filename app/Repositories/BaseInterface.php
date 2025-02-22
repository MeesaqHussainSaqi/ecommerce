<?php
namespace App\Repositories;
use Illuminate\Http\Request;
interface BaseInterface
{
    public function all(Request $request);
    public function find($id);
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    
}
