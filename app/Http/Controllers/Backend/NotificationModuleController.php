<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modals\NotificationModule;
use App\Modals\User;
use App\Models\Notification\Notifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationModuleController extends Controller
{
    public function index(){
        if(User::hasSpecificPermission(Auth::user(),'notification.module.show')){
            $auth_user_id = Auth::user()['id'];
            $notifications = Notifier::getNotifier($auth_user_id);

            $notification_modules = NotificationModule::all();
            return view('backend.notification_modules.index',compact('notification_modules','notifications'));
        }else{
            session()->flash('error', 'You are not authorized for this page!');
            return back();
        }
    }

    public function store(Request $request){
        DB::table('notification_modules')->update(['email' => 0, 'in_app' => 0]);
        if($request->emails){
            NotificationModule::whereIn('event',$request->emails)->update(['email' => 1]);
        }
        if($request->in_app){
            NotificationModule::whereIn('event',$request->in_app)->update(['in_app' => 1]);
        }
        session()->flash('success','Updated Successfully!');
        return redirect()->route('notification.module.index');
    }
}
