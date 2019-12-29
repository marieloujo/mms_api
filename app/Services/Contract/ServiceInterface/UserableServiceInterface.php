<?php

namespace App\Services\Contract\ServiceInterface;

use Illuminate\Http\Request;

interface UserableServiceInterface
{
    public function index();
    public function read($userable);
    public function create(Request $request);
    public function delete($userable);
    public function update(Request $request, $userable);
}