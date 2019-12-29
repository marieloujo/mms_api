<?php

namespace App\Repositories;

use App\Models\Userable;
use App\Repositories\Contracts\AbstractRepository;


class UserableRepository extends AbstractRepository
{
    public function __construct(Userable $userable)
    {
        parent::__construct($userable);
    }
}