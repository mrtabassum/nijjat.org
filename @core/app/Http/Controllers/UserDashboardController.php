<?php

namespace App\Http\Controllers;
use App\Cause;
use App\CauseLogs;
use App\EventAttendance;
use App\EventPaymentLogs;
use App\Events\SupportMessage;
use App\Helpers\DonationHelpers;
use App\Helpers\FlashMsg;
use App\Mail\BasicMail;
use App\Mail\DonationWithdrawRequest;
use App\CauseUpdate;
use App\Notification;
use App\RewardRedeem;
use App\SupportTicket;
use App\SupportTicketMessage;
use App\TaxLog;
use App\User;
use App\DonationWithdraw;
use App\UserFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class UserDashboardController extends Controller
{
    public const BASE_PATH = 'frontend.user.dashboard.';

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function user_index()
    {
        $event_attendances = EventAttendance::where('user_id', $this->logged_user_details()->id)->count();
        $donation = CauseLogs::where('user_id', $this->logged_user_details()->id)->count();
        $campaigns = Cause::where('user_id', $this->logged_user_details()->id)->count();
        $support_tickets = SupportTicket::where('user_id',$this->logged_user_details()->id)->count();
        $total_reward_points = CauseLogs::where('user_id', $this->logged_user_details()->id)->sum('reward_point');
        $total_requested_amount = RewardRedeem::where('user_id', $this->logged_user_details()->id)->get()->pluck('withdraw_request_amount')->sum();
        $requested_points = $total_requested_amount * get_static_option('reward_amount_for_point');
        $donation_reward =  $total_reward_points - $requested_points;

        return view('frontend.user.dashboard.user-home')->with(
            [
                'event_attendances' => $event_attendances,
                'donation' => $donation,
                'campaigns' => $campaigns,
                'support_tickets' => $support_tickets,
                'donation_reward' => $donation_reward,
            ]);
    }

    public function user_email_verify_index()
    {
        $user_details = Auth::guard('web')->user();
        if ($user_details->email_verified == 1) {
            return redirect()->route('user.home');
        }

        if (is_null($user_details->email_verify_token)) {
            User::find($user_details->id)->update(['email_verify_token' => Str::random(6)]);
            $user_details = User::find($user_details->id);
            $message_body = __('Here is your verification code') . ' <span class="verify-code">' . $user_details->email_verify_token . '</span>';
            
            try{
                 Mail::to($user_details->email)->send(new BasicMail([
                    'subject' => __('Verify your email address'),
                    'message' => $message_body
                ]));
                
            }catch(\Exception $e){
                
            }
        }
        return view('frontend.user.email-verify');
    }

    public function reset_user_email_verify_code()
    {
        $user_details = Auth::guard('web')->user();
        if ($user_details->email_verified == 1) {
            return redirect()->route('user.home');
        }
        $message_body = __('Here is your verification code') . ' <span class="verify-code">' . $user_details->email_verify_token . '</span>';
        
        try{
           Mail::to($user_details->email)->send(new BasicMail([
                'subject' => __('Verify your email address'),
                'message' => $message_body
            ]));
        }catch(\Exception $e){
            return redirect()->route('user.email.verify')->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
        
        return redirect()->route('user.email.verify')->with(['msg' => __('Resend Verify Email Success'), 'type' => 'success']);
    }

    public function user_email_verify(Request $request)
    {
        $this->validate($request, [
            'verification_code' => 'required'
        ], [
            'verification_code.required' => __('verify code is required')
        ]);
        $user_details = Auth::guard('web')->user();
        $user_info = User::where(['id' => $user_details->id, 'email_verify_token' => $request->verification_code])->first();

        if (empty($user_info)) {
            return redirect()->back()->with(['msg' => __('your verification code is wrong, try again'), 'type' => 'danger']);
        }

        $user_info->email_verified = 1;
        $user_info->save();
        return redirect()->route('user.home');
    }

    public function user_profile_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,id,'.$request->user_id,
            'phone' => 'nullable|string|max:191',
            'state' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'zipcode' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:191',
            'address' => 'nullable|string',
            'image' => 'nullable|string',
        ], [
            'name.' => __('name is required'),
            'email.required' => __('email is required'),
            'email.email' => __('provide valid email'),
        ]);

        User::find(Auth::guard()->user()->id)->update([

                'name' => $request->name,
                'email' => $request->email,
                'image' => $request->image,
                'phone' => $request->phone,
                'state' => $request->state,
                'city' => $request->city,
                'zipcode' => $request->zipcode,
                'country' => $request->country,
                'address' => $request->address,
            ]);

        return redirect()->back()->with(['type' => 'success', 'msg' => __('Profile Update Success')]);
    }

    public function user_password_change(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ],
            [
                'old_password.required' => __('Old password is required'),
                'password.required' => __('Password is required'),
                'password.confirmed' => __('password must have to be confirmed')
            ]
        );

        $user = User::findOrFail(Auth::guard()->user()->id);

        if (Hash::check($request->old_password, $user->password)) {

            $user->password = Hash::make($request->password);
            $user->save();
            Auth::guard('web')->logout();

            return redirect()->route('user.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }

        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }


    public function event_order_cancel(Request $request)
    {
        $order_details = EventAttendance::where(['id' => $request->order_id, 'user_id' => Auth::guard('web')->user()->id])->first();
        EventAttendance::where('id', $order_details->id)->update([
            'status' => 'cancel'
        ]);
        $event_payment_log = EventPaymentLogs::where(['attendance_id' => $request->order_id])->first();
        $admin_mail = !empty(get_static_option('event_attendance_receiver_mail')) ? get_static_option('event_attendance_receiver_mail') : get_static_option('site_global_email');
        //send mail to admin
        $data['subject'] = __('one of your event booking order has been cancelled');
        $data['message'] = __('hello') . '<br>';
        $data['message'] .= __('your event attendance id') . ' #' . $order_details->id . ' ';
        $data['message'] .= __('has been cancelled by the user.');
        
        try{
            Mail::to($admin_mail)->send(new BasicMail($data));
        }catch(\Exception $e){
            return redirect()->back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
        
        if (!empty($event_payment_log)) {
            //send mail to customer
            $data['subject'] = __('your event booking has benn cancelled');
            $data['message'] = __('hello') . $event_payment_log->name . '<br>';
            $data['message'] .= __('your event attendance id') . ' #' . $order_details->id . ' ';
            $data['message'] .= __('booking status has been changed to cancel.');
            
            //send mail while order status change
            try{
                Mail::to($event_payment_log->email)->send(new BasicMail($data));
            }catch(\Exception $e){
                return redirect()->back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
            }
        }
        
        return redirect()->back()->with(['msg' => __('Order Cancel'), 'type' => 'warning']);
    }

    public function donation_order_cancel(Request $request)
    {
        $order_details = CauseLogs::where(['id' => $request->order_id, 'user_id' => Auth::guard('web')->user()->id])->first();
        CauseLogs::where('id', $order_details->id)->update([
            'status' => 'cancel'
        ]);

        $donation_notify_mail = get_static_option('donation_notify_mail');
        $admin_mail = !empty($donation_notify_mail) ? $donation_notify_mail : get_static_option('site_global_email');

        //send mail to admin
        $data['subject'] = __('one of your donation has been cancelled');
        $data['message'] = __('hello') . '<br>';
        $data['message'] .= __('your donation log id') . ' #' . $order_details->id . ' ';
        $data['message'] .= __('has been cancelled by the user.');
        
        try{
             Mail::to($admin_mail)->send(new BasicMail($data));
        }catch(\Exception $e){
            return redirect()->back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
        
        //send mail to customer
        $data['subject'] = __('your donation has benn cancelled');
        $data['message'] = __('hello') . $order_details->name . '<br>';
        $data['message'] .= __('your donation log id') . ' #' . $order_details->id . ' ';
        $data['message'] .= __('status has been changed to cancel.');
        
        //send mail while order status change
        try{
             Mail::to($order_details->email)->send(new BasicMail($data));
        }catch(\Exception $e){
            return redirect()->back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
       
        return redirect()->back()->with(['msg' => __('donation Cancel'), 'type' => 'warning']);
    }

    public function event_booking()
    {
        $event_attendances = EventAttendance::where('user_id', $this->logged_user_details()->id)->orderBy('id', 'DESC')->paginate(10);
        return view(self::BASE_PATH . 'event-booking')->with(['event_attendances' => $event_attendances]);
    }

    public function donations()
    {
        $donations = CauseLogs::where('user_id', $this->logged_user_details()->id)->orderBy('id', 'DESC')->paginate(10);
        return view(self::BASE_PATH . 'donations')->with(['donation' => $donations]);
    }

    public function edit_profile()
    {
        return view(self::BASE_PATH . 'edit-profile')->with(['user_details' => $this->logged_user_details()]);
    }


    public function change_password()
    {
        return view(self::BASE_PATH . 'change-password');
    }

    public function logged_user_details()
    {
        $old_details = '';
        if (empty($old_details)) {
            $old_details = User::findOrFail(Auth::guard('web')->user()->id);
        }
        return $old_details;
    }


//===================== USER CAMPAIGNS CODE =====================//

    // Cause Update Code
    public function user_all_update_causes($id)
    {
        if(Auth::guard('web')->user()->campaign_permission != 'on'){
            abort(404);
        }
        $all_update_causes = CauseUpdate::where(['cause_id' => $id])->get();
        $cause_id = $id;
        return view(self::BASE_PATH . 'campaigns.cause-update.all-update-cause')->with([
            'all_update_causes' => $all_update_causes,
            'cause_id' => $cause_id
        ]);
    }

    public function new_user_update_cause($id)
    {
        if(Auth::guard('web')->user()->campaign_permission != 'on'){
            abort(404);
        }
        $cause_id = $id;
        return view(self::BASE_PATH . 'campaigns.cause-update.new-update-cause', compact('cause_id'));
    }

    public function store_update_causes(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',

        ], [
            'title.required' => __('title is required'),
            'status.required' => __('status is required'),
        ]);

        $id = CauseUpdate::create([
            'title' => $request->title,
            'cause_id' => $request->cause_id,
            'description' => $request->description,
            'image' => $request->image,
        ])->id;

        Cause::where('id', $request->cause_id)->update(['cause_update_id' => $id]);

        return redirect()->back()->with(['msg' => __('New item added'), 'type' => 'success']);
    }


    public function update_update_causes(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'nullable|string',
        ], [
            'title.required' => __('title is required'),
        ]);

        CauseUpdate::findOrFail($request->case_update_id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
            'cause_id' => $request->cause_id,
        ]);

        return redirect()->back()->with(['msg' => __('Update success'), 'type' => 'success']);

    }

    public function delete_update_cause(Request $request, $id)
    {
        CauseUpdate::findOrFail($id)->delete();
        return redirect()->back()->with(['msg' => __('item deleted'), 'type' => 'danger']);
    }

    // User Campaign Log and Withdraw Code
    public function campaign_log_withdraw()
    {
        if(Auth::guard('web')->user()->campaign_permission != 'on'){
            abort(404);
        }
        $causes = Cause::where('user_id', $this->logged_user_details()->id)->get();
        $withdraw_logs = DonationWithdraw::where('user_id', $this->logged_user_details()->id)->paginate(10);
        return view(self::BASE_PATH . 'campaigns.campaign-log-withdraw')->with(['causes' => $causes, 'withdraw_logs' => $withdraw_logs]);
    }

    public function campaign_withdraw_view($id){
        $withdraw = DonationWithdraw::where(['user_id' => $this->logged_user_details()->id,'id' => $id])->first();
        return view(self::BASE_PATH.'campaigns.withdraw-view')->with(['withdraw' => $withdraw]);
    }

    public function campaign_withdraw_submit(Request $request)
    {
        if(Auth::guard('web')->user()->campaign_permission != 'on'){
            abort(404);
        }
        $request->validate([
            'payment_gateway' => 'required',
            'withdraw_request_amount' => 'required',
            'payment_account_details' => 'required',
            'additional_comment_by_user' => 'nullable',
        ]);
        $user_details = User::find($this->logged_user_details()->id);
        $cause = Cause::where(['id' => $request->cause_id, 'user_id' => $this->logged_user_details()->id,'status' => 'publish'])->first();
        if(empty($cause)){
             return redirect()->back()->with(['msg' => __('Your Campagian is not yet approve or not exists'), 'type' => 'danger']);
        }
        if($request->withdraw_request_amount < 1){
             return redirect()->back()->with(['msg' => __('you can not withdraw less than').' '.amount_with_currency_symbol(1), 'type' => 'danger']);
        }
        $raised_amount = (int)$cause->raised;
        $all_withdraws = DonationWithdraw::where(['donation_id' => $cause->id, 'user_id' => $this->logged_user_details()->id])->where('payment_status', '!=', 'reject')->get()->pluck('withdraw_request_amount')->sum();
        $available_amount = ($raised_amount < $all_withdraws) ? 0 : $raised_amount - $all_withdraws;

        if ($request->withdraw_request_amount > $available_amount) {
            return back()->with(FlashMsg::item_delete(__('you withdraw amount is getter than your raised amount')));
        }

        $withdraw_id = DonationWithdraw::create([
            'donation_id' => $request->cause_id,
            'user_id' => $this->logged_user_details()->id,
            'payment_gateway' => $request->payment_gateway,
            'withdraw_request_amount' => $request->withdraw_request_amount,
            'payment_account_details' => $request->payment_account_details,
            'additional_comment_by_user' => $request->additional_comment_by_user,
        ]);

        if(!empty($withdraw_id)){
            Notification::create([
                'withdraw_id'=> $withdraw_id->id,
                'title'=> 'New campaign withdraw request',
                'type'=> 'cause_withdraw',
            ]);
        }
        
        $admin_mail = get_static_option('site_global_email');

        try{
            Mail::to($admin_mail)->send(new DonationWithdrawRequest([
                'subject' => __('You have new donation withdrawal Message'),
                'user_name' => $user_details->name ?? __('user not found'),
                'amount' => $request->withdraw_request_amount,
            ]));
        }catch(\Exception $e){
            return redirect()->back()->with(['msg' => $e->getMessage(), 'type' => 'success']);
        }
        
        return redirect()->back()->with(['msg' => __('Your Withdraw Request has been sent'), 'type' => 'success']);
    }


    public function campaign_withdraw_check(Request $request)
    {
        $cause = Cause::where(['id' => $request->id, 'user_id' => $this->logged_user_details()->id,'status' => 'publish'])->first();
        if(empty($cause)){
             return response()->json([
                'withdraw_sum' => 0,
                'raised_amount' => 0,
                'available_amount' => 0,
            ]);
        }
        $raised_amount = (int)$cause->raised;
        $donation_charge_form = get_static_option('donation_charge_form');
         
        if($donation_charge_form === 'campaign_owner'){
            $raised_amount -=  DonationHelpers::get_donation_charge_for_campaign_owner($cause->raised);
        }
        
        
        $all_withdraws = DonationWithdraw::where(['donation_id' => $cause->id])->where('payment_status', '!=', 'reject')->get()->pluck('withdraw_request_amount')->sum();
        $available_amount = ($raised_amount < $all_withdraws) ? 0 : $raised_amount - $all_withdraws;
       
        if($donation_charge_form === 'campaign_owner'){
             $available_amount -=  DonationHelpers::get_donation_charge_for_campaign_owner($available_amount);
        }
        
        return response()->json([
            'withdraw_sum' => $all_withdraws,
            'raised_amount' => $raised_amount,
            'available_amount' => ($available_amount < 1) ? 0 : $available_amount,
            'admin_charge' => DonationHelpers::get_donation_charge_for_campaign_owner($available_amount),
        ]);
    }

    //Support Tickets
    public function support_tickets(){
        $all_tickets = SupportTicket::where('user_id',$this->logged_user_details()->id)->paginate(10);
        return view(self::BASE_PATH.'support-tickets')->with([ 'all_tickets' => $all_tickets]);
    }

    public function support_ticket_priority_change(Request $request){
        $this->validate($request,[
            'priority' => 'required|string|max:191'
        ]);
        SupportTicket::findOrFail($request->id)->update([
            'priority' => $request->priority,
        ]);
        return 'ok';
    }

    public function support_ticket_status_change(Request $request){
        $this->validate($request,[
            'status' => 'required|string|max:191'
        ]);
        SupportTicket::findOrFail($request->id)->update([
            'status' => $request->status,
        ]);
        return 'ok';
    }
    public function support_ticket_view(Request $request,$id){
        $ticket_details = SupportTicket::findOrFail($id);
        $all_messages = SupportTicketMessage::where(['support_ticket_id'=>$id])->get();
        $q = $request->q ?? '';
        return view(self::BASE_PATH.'view-ticket')->with(['ticket_details' => $ticket_details,'all_messages' => $all_messages,'q' => $q]);
    }

    public function support_ticket_message(Request $request){
        $this->validate($request,[
            'ticket_id' => 'required',
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'send_notify_mail' => 'nullable|string',
            'file' => 'nullable|mimes:zip',
        ]);

        $ticket_info = SupportTicketMessage::create([
            'support_ticket_id' => $request->ticket_id,
            'type' => $request->user_type,
            'message' => $request->message,
            'notify' => $request->send_notify_mail ? 'on' : 'off',
        ]);

        if ($request->hasFile('file')){
            $uploaded_file = $request->file;
            $file_extension = $uploaded_file->getClientOriginalExtension();
            $file_name =  pathinfo($uploaded_file->getClientOriginalName(),PATHINFO_FILENAME).time().'.'.$file_extension;
            $uploaded_file->move('assets/uploads/ticket',$file_name);
            $ticket_info->attachment = $file_name;
            $ticket_info->save();
        }

        //send mail to user
        event(new SupportMessage($ticket_info));
        return back()->with(FlashMsg::item_update(__('Message send')));
    }

    public function tax_page()
    {
        $user_id = $this->logged_user_details()->id;
        $user_details = User::where('id',$user_id)->first();
        return view(self::BASE_PATH.'tax',compact('user_details'));
    }

    public function tax_information_update(Request $request){
            $request->validate([
                'monthly_income' => 'required|integer',
                'annual_income' => 'required|integer',
                'income_source' => 'required|string',
                'nid_image' => 'required',
                'driving_license_image' => 'required',
                'passport_image' => 'required',
            ]);

             $user_id = $this->logged_user_details()->id;
             User::where('id', $user_id)->update([
                 'monthly_income' => $request->monthly_income,
                 'annual_income' => $request->annual_income,
                 'income_source' => $request->income_source,
                 'nid_image' =>  $request->nid_image,
                 'driving_license_image' =>  $request->driving_license_image,
                 'passport_image' => $request->passport_image
             ]);

         return back()->with(FlashMsg::item_update(__('Tax Information Updated Successfully.. ')));
    }

    public function tax_request_log()
    {
        $user_id = $this->logged_user_details()->id;
        $all_request_tax_logs = TaxLog::where('user_id',$user_id)->get();
        return view(self::BASE_PATH.'tax-request-log',compact('all_request_tax_logs'));
    }

    public function tax_request_store(Request $request){


        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'title' => 'required|string',
        ]);

        TaxLog::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'attachment' => null,
            'status' => 'pending',
        ]);

        return back()->with(FlashMsg::item_update(__('Tax Certificate Request Submitted Successfully..')));
    }

    public function user_follow_store(Request $request)
    {
        $auth_id = Auth::guard('web')->id();
        $campaign_owner_id = $request->campaign_owner_id;
        $user_type = $request->user_type;
        $text = $request->text == 'Follow' ? 'Following' : 'Follow';

        $follow_data = UserFollow::where('user_id', $auth_id)
                            ->where('campaign_owner_id', $campaign_owner_id )
                            ->first();

        if (!$follow_data) {
            $user_follow = UserFollow::create([
                'user_id' => $auth_id,
                'campaign_owner_id' => $campaign_owner_id,
                'user_type' => $user_type,
                'follow_status' => 'follow',
            ]);

            return response()->json([
                'type' => $user_follow ? 'success' : 'fail',
                'text' => $text,
            ]);
        }

        if ($follow_data) {
            $follow_data->update([
                'follow_status' => $follow_data->follow_status == 'follow' ? 'unfollow' : 'follow',
                 'user_type' => $user_type,
            ]);
        }

        return response()->json([
            'type' => 'updated',
            'text' => $text,
        ]);
    }

    public function following_user_campaigns(Request $request)
    {
        $campaign_owner_user = UserFollow::where(['follow_status' => 'follow','user_id'=> Auth::guard('web')->id(), 'user_type'=>'user'])
            ->orWhere(['user_type'=>'admin'])->get();

        $admin_ids = $campaign_owner_user->filter(function($item){
            return $item->user_type === 'admin' && $item->follow_status == 'follow' ;
        })->pluck('campaign_owner_id');

        $user_ids = $campaign_owner_user->filter(function($item){
            return $item->user_type === 'user' && $item->follow_status == 'follow' ;
        })->pluck('campaign_owner_id');

        $all_follower_donations = Cause::where(['status' => 'publish'])
            ->where('admin_id',current($admin_ids))
            ->orWhere('user_id',current($user_ids))
            ->distinct('admin_id')
            ->distinct('user_id')
            ->get()->unique('admin_id')->unique('user_id');

       return view(self::BASE_PATH.'campaigns.following-user-campaigns')->with(['all_follower_donations' => $all_follower_donations]);
    }

    public function reward_points()
    {
         $all_rewards =  CauseLogs::where('user_id', $this->logged_user_details()->id)->where('reward_point', '>',0)->get();
          return view(self::BASE_PATH.'reward.reward-points')->with(['all_rewards' => $all_rewards]);

    }

    public function reward_redeem_logs()
    {
        $reward_redeem_logs = RewardRedeem::where('user_id', $this->logged_user_details()->id)->paginate(10);

        $total_requested_amount = RewardRedeem::where('user_id', $this->logged_user_details()->id)->get()->pluck('withdraw_request_amount')->sum();
        $redeem_balance = CauseLogs::where('user_id', $this->logged_user_details()->id)->sum('reward_amount');
        return view(self::BASE_PATH . 'reward.reward-redeem-log')->with([
            'redeem_balance'=> $redeem_balance,
            'reward_redeem_logs' => $reward_redeem_logs,
            'total_requested_amount'=>$total_requested_amount
        ]);
    }

    public function reward_redeem_check(Request $request)
    {
        if(empty($request->amount)){
            return response()->json(['withdraw_sum' => 0,'available_amount' => 0]);
        }

          $reward_amount = $request->amount;
          $all_withdraws = RewardRedeem::where(['user_id' => $this->logged_user_details()->id])->where('payment_status', '!=', 'reject')->get()->pluck('withdraw_request_amount')->sum();
          $available_amount = ($all_withdraws < $reward_amount) ? ($reward_amount - $all_withdraws) : 0;


        return response()->json([
            'available_amount' => $available_amount,
        ]);
    }

    public function reward_redeem_submit(Request $request)
    {

        $request->validate([
            'payment_gateway' => 'required',
            'withdraw_request_amount' => 'required',
            'payment_account_details' => 'required',
            'additional_comment_by_user' => 'nullable',
        ]);

        $cause_log_total_amount = CauseLogs::where([ 'user_id' => $this->logged_user_details()->id,'status' => 'complete'])->get()->pluck('reward_amount')->sum();

        if($request->withdraw_request_amount < 1){
            return redirect()->back()->with(['msg' => __('you can not withdraw less than').' '.amount_with_currency_symbol(1), 'type' => 'danger']);
        }

        $rewared_amount = $cause_log_total_amount;
        $all_withdraws = RewardRedeem::where([ 'user_id' => $this->logged_user_details()->id])->where('payment_status', '!=', 'reject')->get()->pluck('withdraw_request_amount')->sum();
        $available_amount = ($rewared_amount < $all_withdraws) ? 0 : $rewared_amount - $all_withdraws;

        if ($request->withdraw_request_amount > $available_amount) {
            return back()->with(FlashMsg::item_delete(__('you redeem amount is getter than your reward amount')));
        }


        RewardRedeem::create([
            'donation_id' => null,
            'donation_log_id' => null,
            'user_id' => $this->logged_user_details()->id,
            'payment_gateway' => $request->payment_gateway,
            'withdraw_request_amount' => $request->withdraw_request_amount,
            'payment_account_details' => $request->payment_account_details,
            'additional_comment_by_user' => $request->additional_comment_by_user,
        ]);

        $admin_mail = get_static_option('site_global_email');

        try{
            Mail::to($admin_mail)->send(new DonationWithdrawRequest([
                'subject' => __('You have new reward redeem Message'),
                'user_name' => $user_details->name ?? __('user not found'),
                'amount' => $request->withdraw_request_amount,
            ]));

        }catch(\Exception $e){
            return redirect()->back()->with(['msg' => $e->getMessage(), 'type' => 'success']);
        }

        return redirect()->back()->with(['msg' => __('Your Redeem Request has been sent'), 'type' => 'success']);

    }

    public function reward_redeem_view($id){

        if(empty($id)){
            abort(404);
        }
         $redeem = RewardRedeem::where(['user_id' => $this->logged_user_details()->id,'id' => $id])->first();
         $total_reward_amount = CauseLogs::where(['user_id' => $this->logged_user_details()->id])->get()->pluck('reward_amount')->sum();
        return view(self::BASE_PATH.'reward.redeem-view')->with(['redeem' => $redeem, 'total_reward_amount'=>$total_reward_amount]);
    }



}
