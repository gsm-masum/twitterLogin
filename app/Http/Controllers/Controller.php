<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function successResponse($result, $msg)
    {
        $res = [
            'status' => 200,
            'success' => true,
            'data' => $result,
            'message' => $msg,
        ];
        return response()->json($res, 200);
    }

    public function failedResponse($error, $errorMsg = [], $code = 200)
    {
        $res = [
            'status' => 400,
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMsg)) {
            $res['data'] = $errorMsg;
        }

        return response()->json($res, $code);
    }

    public function getClientIPaddress()
    {

        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $clientIp = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $clientIp = $forward;
        } else {
            $clientIp = $remote;
        }

        return $clientIp;
    }
}
