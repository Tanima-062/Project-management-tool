<?php

namespace App\Http\Controllers\Backend\Notification;

use App\Http\Controllers\Base\BaseController;
use App\Models\Notification\FcmDeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@gmail.com]
 * @create date 2021-03-23 14:59:25
 * @modify date 2021-03-23 14:59:25
 * @desc [description]
 */

class PushNotificationController extends BaseController {
      /** 
     * Write code on Method
     *
     * @return response()
     */
    public function saveToken(Request $request)
    {
        $token = $request->token;
        if($token && isset(Auth::user()->id)) {
            $user = Auth::user();
            $fcmToken = FcmDeviceToken::where('user_id', $user->id)->where('device_token', $token)->first();
            if(!$fcmToken) {
                $newToken = new FcmDeviceToken();
                $newToken->user_id = $user->id;
                $newToken->device_token = $token;
                $newToken->save();
            }
        }
    }
  
}