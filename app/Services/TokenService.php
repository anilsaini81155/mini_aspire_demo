<?php

namespace App\Services;

use App\Http\Repository;
use Illuminate\Support\Collection as Collect;
use DB;
use Carbon\Carbon;
use Log;

class TokenService
{
    protected $sysConfigRepo;
    protected $tokenRepo;

    public function __construct(Repository\SysConfigRepository $sysConfigRepo, Repository\TokenRepository $tokenRepo)
    {
        $this->sysConfigRepo = $sysConfigRepo;
        $this->tokenRepo = $tokenRepo;
    }


    public function processUserForToken($userDetails)
    {
        
         return  $this->generateToken($userDetails);
        
    }

    public function generateToken($user)
    {
        DB::beginTransaction();

        try {

            $data = $this->sysConfigRepo->getSysDetails(['name' => Config('commonconfig.Token_Generation_Json_Key'), 'status' => 'Active', 'is_deleted' => 'False']);
            
            if ($data == false) {
                return false;
            }
            
            $key = hash('sha256', $data->config);
            $curr_time= now() ;
            $request = [
                'contact_no' => $user->contact_no,
                'id' => $user->id,
                'created_at' => $curr_time
            ];

            $requestData = json_encode($request);
            
            $token = hash_hmac('sha256', $requestData, $key);
            
            $this->tokenRepo->insert(['user_id' => $user->id, 'expires_at' => Carbon::now()->addDay(config('commonconfig.Token_Expiry'))->format('Y-m-d H:i:s'), 'token' => $token ,'revoked' => 0 ,'created_at' => $curr_time]);
        
            DB::commit();

            return $token;
        } catch (\Exception $ex) {
            
            DB::rollback();
            Log::info($ex);
            return false;
        }
    }

   
}
