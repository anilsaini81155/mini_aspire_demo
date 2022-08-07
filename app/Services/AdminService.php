<?php

namespace App\Services;

use App\Http\Repository;
use Illuminate\Support\Collection as Collect;
use DB;

class AdminService
{
    protected $tokenRepo;

    public function __construct(Repository\TokenRepository $tokenRepo)
    {
        $this->tokenRepo = $tokenRepo;
    }

   urn  $this->tokenRepo->update(['revoked' => 1], $rslt->id);
    }
}
