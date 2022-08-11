<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['title','cause_log_id','user_campaign_id','withdraw_id','seen','type'];

    public function cause_log(){
        return $this->belongsTo(CauseLogs::class, 'cause_log_id','id');
    }

    public function cause_withdraw(){
        return $this->belongsTo(DonationWithdraw::class, 'withdraw_id','id');
    }

    public function user_campaign(){
        return $this->belongsTo(Cause::class, 'user_campaign_id','id');
    }
}
