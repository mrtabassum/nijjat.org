<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Mail\SubscriberMessage;
use App\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterPageManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:register-page-manage');
    }

    public function register_page_setting(){

        return view('backend.pages.register-page-manage');
    }

    public function update_register_page_setting(Request $request){

        $this->validate($request,[
            'register_page_terms_of_service_url' => 'nullable|string',
            'register_page_privacy_policy_url' => 'nullable|string',
        ]);

        $data = [
            'register_page_terms_of_service_url',
            'register_page_privacy_policy_url',
        ];

        foreach ($data as $item){
            if($request->has($item)){
                update_static_option($item,$request->$item);
            }
        }

        return redirect()->back()->with(FlashMsg::settings_update());

    }
}
