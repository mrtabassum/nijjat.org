<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Notification;
use App\Reward;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }

    public function index(){
        $all_notifications = Notification::latest()->get();
        return view('backend.pages.notification.index')->with(['all_notifications' => $all_notifications]);
    }

    public function view($id){
        $notification = Notification::findOrFail($id);
        $notification->seen == 0 ? $notification->seen = 1 : 1;
        $notification->save();
        return view('backend.pages.notification.view')->with(['notification' => $notification]);
    }

    public function delete(Request $request,$id){
        Notification::findOrFail($id)->delete();
        return redirect()->back()->with(['msg' => __('item deleted'),'type' => 'danger']);
    }

    public function bulk_action(Request $request){
        $all = Notification::findOrFail($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

}
