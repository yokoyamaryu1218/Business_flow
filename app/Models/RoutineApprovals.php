<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutineApprovals extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'routine_id',
        'creator_id',
        'approver_id',
        'approved',
        'approval_at',
    ];
}
