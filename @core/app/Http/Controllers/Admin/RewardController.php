<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:reward-list|reward-create|reward-edit|reward-delete',['only' => ['index']]);
        $this->middleware('permission:reward-create',['only' => ['store_reward']]);
        $this->middleware('permission:reward-edit',['only' => ['update_reward']]);
        $this->middleware('permission:reward-delete',['only' => ['delete_reward','bulk_action']]);
    }

    public function index(){
        $all_reward = Reward::latest()->get();
        return view('backend.pages.reward.reward')->with(['all_reward' => $all_reward]);
    }

    public function store(Request $request){

        $this->validate($request,[
            'reward_title' => 'required|max:191',
            'reward_goal_from' => 'required|unique:rewards|alpha_num',
            'reward_goal_to' => 'required|unique:rewards|alpha_num',
            'reward_point' => 'required|alpha_num',
            'reward_expire_date' => 'required|string',
            'status' => 'required|string',
        ],[
            'title.required' => __('title is required'),
            'status.required' => __('status is required'),
        ]);

        $from_amount = $request->reward_goal_from;
        $to_amount = $request->reward_goal_to;

        if($from_amount >= $to_amount){
            return redirect()->back()->with(FlashMsg::settings_delete('Reward To Amount Has to greater than Reward to amount!'));
        }

        Reward::create([
            'reward_title' => $request->reward_title,
            'reward_goal_from' => $from_amount,
            'reward_goal_to' => $to_amount,
            'reward_point' => $request->reward_point,
            'reward_amount' => $request->reward_amount,
            'reward_expire_date' => $request->reward_expire_date,
            'status' => $request->status
        ]);

        return redirect()->back()->with(['msg' => __('New Reward item added'),'type' => 'success']);
    }

    public function update(Request $request){


        $this->validate($request,[
            'reward_title' => 'required|max:191',
            'reward_goal_from' => 'required|alpha_num',
            'reward_goal_to' => 'nullable|alpha_num',
            'reward_point' => 'nullable|alpha_num',
            'reward_expire_date' => 'required|string',
            'status' => 'required|string',
        ],[
            'title.required' => __('title is required'),
            'status.required' => __('status is required'),
        ]);

        $from_amount = $request->reward_goal_from;
        $to_amount = $request->reward_goal_to;

        if($from_amount >= $to_amount){
            return redirect()->back()->with(FlashMsg::settings_delete('Reward to Amount Has to greater than Reward from amount!'));
        }

        Reward::findOrFail($request->id)->update([

            'reward_title' => $request->reward_title,
            'reward_goal_from' => $from_amount,
            'reward_goal_to' => $to_amount,
            'reward_point' => $request->reward_point,
            'reward_amount' => $request->reward_amount,
            'reward_expire_date' => $request->reward_expire_date,
            'status' => $request->status
        ]);

        return redirect()->back()->with(['msg' => __('Update success'),'type' => 'success']);
    }

    public function delete(Request $request,$id){
        Reward::findOrFail($id)->delete();
        return redirect()->back()->with(['msg' => __('item deleted'),'type' => 'danger']);
    }

    public function bulk_action(Request $request){
        $all = Reward::findOrFail($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function settings()
    {
        return view('backend.pages.reward.settings');
    }

    public function update_settings(Request $request)
    {
        $this->validate($request, [
            'reward_amount_for_point'=> 'required|alpha_num'
        ]);

        update_static_option('reward_amount_for_point',$request->reward_amount_for_point);

        return redirect()->back()->with(FlashMsg::settings_update());

    }

}
