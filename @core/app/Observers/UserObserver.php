<?php

namespace App\Observers;

use App\Mail\BasicMail;
use App\User;
use Illuminate\Support\Facades\Mail;

class UserObserver
{

    public function created(User $user)
    {
        $email = get_static_option('site_global_email');
        try {
            $message_body = __('New user registered : ').' <span class="user-registration">'.$user['name'].'</span>';
            Mail::to($email)->send(new BasicMail([
                'subject' => __('New user registration'),
                'message' => $message_body
            ]));

        }catch (\Exception $e){
            //handle error
        }
    }

}
