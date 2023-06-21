<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedureApprovals extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'procedure_id',
        'creator_id',
        'approver_id',
        'document_id',
        'approved',
        'approval_at',
    ];
}
