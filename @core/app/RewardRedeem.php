<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardRedeem extends Model
{
    use HasFactory;

    protected $table = 'reward_redeems';
    protected $fillable = ['donation_id','user_id','donation_log_id','payment_gateway','withdraw_request_amount','payment_account_details',
        'additional_comment_by_user','transaction_id','additional_comment_by_admin','payment_receipt','payment_status','payment_information'];


    public function cause()
    {
        return $this->belongsTo(Cause::class,'donation_id','id');
    }

    public function log()
    {
        return $this->belongsTo(CauseLogs::class,'donation_log_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
