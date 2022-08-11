<?php

namespace App\Http\Controllers\Admin;

use App\Cause;
use App\CauseLogs;
use App\RewardRedeem;
use App\Helpers\DataTableHelpers\Donation;
use App\Helpers\DataTableHelpers\General;
use App\Helpers\DataTableHelpers\Reward;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class RewardRedeemController extends Controller
{
    private const BASE_PATH = 'backend.pages.reward.';

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:reward-redeem-list|reward-redeem-edit|reward-redeem-delete|reward-redeem-view',['only' => ['all_reward_redeem']]);
        $this->middleware('permission:reward-redeem-edit',['only' => ['edit_reward_redeem','update_reward_redeem','redeem_Approval']]);
        $this->middleware('permission:reward-redeem-delete',['only' => ['delete_reward_redeem','bulk_action']]);
        $this->middleware('permission:reward-redeem-view',['only' => ['view_reward_redeem']]);
    }

    public function all_reward_redeem(Request $request)
    {
        if ($request->ajax()){
            $redeems = RewardRedeem::orderBy('id','desc')->get();
            return DataTables::of($redeems)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return General::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row){
                    return Reward::withdrawInfoColumn($row);
                })
                ->addColumn('status',function ($row){
                    return General::statusSpan($row->payment_status);
                })
                ->addColumn('action', function($row){
                    $action = '';
                    $admin = auth()->guard('admin')->user();
                    if ($admin->can('permission:reward-redeem-delete')){
                        $action .= General::deletePopover(route('admin.reward.redeem.delete',$row->id));
                    }
                    if ($admin->can('permission:reward-redeem-view')){
                        $action .= General::viewIcon(route('admin.reward.redeem.view',$row->id));
                    }
                    if ($admin->can('reward-redeem-edit')){
                        if($row->payment_status !== 'approved'){
                            $action .= General::editIcon(route('admin.reward.redeem.edit',$row->id));
                        }
                    }

                    return $action;
                })
                ->rawColumns(['action','checkbox','info','status'])
                ->make(true);

        }
        return  view(self::BASE_PATH . 'redeem');
    }


    public function edit_reward_redeem($id)
    {
        $redeem = RewardRedeem::findOrFail($id);
        $user_id = optional($redeem->user)->id;
        $redeem_balance = CauseLogs::where('user_id', $user_id)->sum('reward_amount');

        return view(self::BASE_PATH . 'edit-redeem')->with([
            'redeem' => $redeem,
            'redeem_balance' => $redeem_balance,
        ]);
    }

    public function update_reward_redeem(Request $request)
    {
        $this->validate($request, [
            'transaction_id' => 'nullable|string',
            'payment_information' => 'nullable|string',
            'additional_comment_by_admin' => 'nullable|string',
            'payment_receipt' => 'nullable|mimes:pdf,jpg,jpeg,png',
        ]);

        $redeem = RewardRedeem::find($request->withdraw_id);

        $user_id = optional($redeem->user)->id;
        $redeem_balance = CauseLogs::where('user_id', $user_id)->sum('reward_amount');

        $redeem_able_amount = $redeem_balance - $redeem->where('payment_status', 'approved')->pluck('withdraw_request_amount')->sum();


        if ($request->file('payment_receipt')) {

            if (file_exists('assets/uploads/reward-redeem/' . $redeem->payment_receipt)) {
                @unlink('assets/uploads/reward-redeem/' . $redeem->payment_receipt);
            }
            $attachment = $request->file('payment_receipt');
            $attachmentName = 'payment_receipt_' . uniqid('', true) . '.' . $attachment->getClientOriginalExtension();
            $folder_path = 'assets/uploads/reward-redeem/';
            $attachment->move($folder_path, $attachmentName);
        } else {
            $attachmentName = $redeem->payment_receipt;
        }

        RewardRedeem::findOrFail($request->withdraw_id)->update([
            'transaction_id' => $request->transaction_id,
            'payment_information' => $request->payment_information,
            'additional_comment_by_admin' => $request->additional_comment_by_admin,
            'payment_receipt' => $attachmentName,
            'payment_status' => $request->payment_status,
        ]);

        $user_email = optional($redeem->log)->email;
        if ($user_email) {
            try{
                Mail::to($user_email)->send(new BasicMail([
                    'subject' => __('Your reward redeem Status Has Been Change'),
                    'message' => __('Status is ') . ": " . $request->payment_status . '<br>' . $request->additional_comment_by_admin
                ]));
            }catch(\Exception $e){
                return  redirect()->back()->with(['msg' => __('Reward Redeem Updated').' '.__('Mail Send Failed'), 'type' => 'success']);
            }
        }


        return redirect()->back()->with(['msg' => __('Reward Redeem Updated...'), 'type' => 'success']);
    }


    public function redeem_Approval($id)
    {
        $redeem_approval = RewardRedeem::findOrFail($id);

        $raised_amount = $redeem_approval->donation->raised;
        $user_withdraw_amount_request = $redeem_approval->withdraw_request_amount;

        $user_withdrawable_amount = $redeem_approval->campaign_withdrawable_amount;

        $deduction_raised_amount = ($raised_amount - $user_withdraw_amount_request);

        $cause_id = $redeem_approval->donation->id;
        Cause::where('id', $cause_id)->update(['raised' => $deduction_raised_amount]);
        RewardRedeem::where('id', $id)->update(['payment_status' => 'approved']);

        if ($user_withdrawable_amount > $user_withdraw_amount_request) {
            RewardRedeem::where('id', $id)->update(['transaction_status' => 'not-full-paid']);
        } else {
            RewardRedeem::where('id', $id)->update(['transaction_status' => 'full-paid']);
        }

        $user_email = $redeem_approval->user->email;
        try{
            Mail::to($user_email)->send(new BasicMail([
                'subject' => __('Your reward redeem request has been approved and you will get back your withdrawalbe amount soon.'),
                'message' => "Status is : Changed"
            ]));
        }catch(\Exception $e){
            return redirect()->back()->with(['msg' => __('Reward Redeem Approved...').' '.__('Mail Send Failed'), 'type' => 'success']);
        }

        return redirect()->back()->with(['msg' => __('Reward Redeem Approved...'), 'type' => 'success']);

    }


    public function delete_reward_redeem(Request $request, $id)
    {
        $data = RewardRedeem::findOrFail($id);
        if (file_exists('assets/uploads/reward-redeem/'.$data->payment_receipt)) {
            @unlink('assets/uploads/reward-redeem/'.$data->payment_receipt);
        }

        RewardRedeem::findOrFail($id)->delete();
        return redirect()->back()->with([ 'msg' => __('Reward Redeem Deleted...'), 'type' => 'danger ']);
    }

    public function bulk_action(Request $request)
    {
        $all = RewardRedeem::findOrFail($request->ids);
        foreach ($all as $item) {
            if (file_exists('assets/uploads/reward-redeem/' . $item->payment_receipt)) {
                @unlink('assets/uploads/reward-redeem/' . $item->payment_receipt);
            }
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function view_reward_redeem($id){
        $redeem = RewardRedeem::findOrFail($id);
        $user_id = optional($redeem->user)->id;
        $redeem_balance = CauseLogs::where('user_id', $user_id)->sum('reward_amount');
        $total_requested_amount = RewardRedeem::where('user_id', $user_id)->get()->pluck('withdraw_request_amount')->sum();



        return view(self::BASE_PATH.'redeem-view')->with([
            'redeem' => $redeem,
            'total_requested_amount'=>$total_requested_amount,
            'redeem_balance'=>$redeem_balance,
        ]);
    }

}
