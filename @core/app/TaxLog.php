<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxLog extends Model
{
    use HasFactory;

    protected $table = 'tax_logs';
    protected $fillable = ['user_id','title','start_date','end_date','attachment','status'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
