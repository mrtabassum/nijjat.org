<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'rewards';
    protected $fillable = ['reward_title','reward_goal_from','reward_goal_to','reward_point','reward_amount','reward_expire_date','status'];
}
