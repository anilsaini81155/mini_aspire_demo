<?php


namespace App\Library;

use App\Contracts\AuthCheck;
use App\Http\Repository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthCheckLib implements AuthCheck
{

    protected $userRepo;
    protected $tokenRepo;

    public function __construct(Repository\TokenRepository $tokenRepo,Repository\UserRepository $userRepo)
    {

        $this->userRepo = $userRepo;
        $this->tokenRepo = $tokenRepo;
    }

    public function checkTokenAuthenticity($rqst)
    {   
        $header = getallheaders()['Authorization'];
        if (Str::startsWith($header, 'Bearer ')) {
            $header = Str::substr($header, 7);
        }

        $getTokenDeatils = $this->tokenRepo->getTokenDetails(['token' => $header, 'revoked' => 0]);

        if ($getTokenDeatils == false) {
            return false;
        }

        $getUserDeatils = $this->userRepo->getDetails(['id' => $getTokenDeatils->user_id]);

        if ($getUserDeatils == false) {
            return false;
        }

        if (Carbon::now()->format('Y-m-d H:i:s') < Carbon::parse($getTokenDeatils->expires_at)->format('Y-m-d H:i:s')) {
            return ['user_id' => $getTokenDeatils->user_id];
        } else {
            
            return false;
        }
    }
}
