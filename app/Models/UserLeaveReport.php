<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLeaveReport extends Model
{
    use HasFactory;
    protected $table = 'user_total_leaves';

    protected $fillable = [
        'user_id',
        'year',
        'total_leaves',
        'carry_forward'
    ];
}
