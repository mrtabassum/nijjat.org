<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollow extends Model
{
    use HasFactory;

    protected $table = 'user_follow';
    protected $fillable = ['user_id','campaign_owner_id','follow_status','user_type'];

    public function user()
    {
        $model = $this->attributes['created_by'] === 'user' ?  User::class : Admin::class;
        $foreign_key = 'campaign_owner_id';

        return $this->belongsTo($model,$foreign_key,'id');
    }
}
