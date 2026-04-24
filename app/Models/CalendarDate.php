<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CalendarDate extends Model
{
    protected $table = 'calendar_dates';
    protected $fillable = ['user_id', 'date', 'type'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
