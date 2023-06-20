<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProcedureDocument;
use App\Models\Routine;

class Procedure extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'task_id',
        'previous_procedure_id',
        'next_procedure_ids',
        'is_visible',
    ];

    public function procedureDocument()
    {
        return $this->hasMany(ProcedureDocument::class);
    }

    public function routine()
    {
        return $this->hasMany(Routine::class);
    }
}
