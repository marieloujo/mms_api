<?php


namespace App\Services;

use App\OAuthClient;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserableRepository;
use App\Services\Contract\AbstractService;
use App\Services\Contract\ServiceInterface\UserableServiceInterface;
use Illuminate\Http\Request;

class UserableService extends AbstractService implements UserableServiceInterface
{
    public function __construct(UserableRepository $repository)
    {
        parent::__construct($repository);
    }

    public function index()
    {
       return parent::index();
    }
    public function read($userable)
    {
       return parent::read($userable);
    }

    public function create(Request $request)
    {
       return parent::create($request);
    }

    public function update(Request $request,$userable)
    {
       return parent::update($request,$userable);
    }
    
    public function delete($userable)
    {
       return parent::delete($userable);
    }
}