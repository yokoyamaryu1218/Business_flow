<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'document_id',
        'process',
        'changes',
        'creator_id',
        'changes_id',
        'befored_at',
    ];
}
