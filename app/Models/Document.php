<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProcedureDocument;

class Document extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'document_number',
        'title',
        'file_name',
        'is_visible',
    ];

    public function procedureDocument()
    {
        return $this->hasMany(ProcedureDocument::class);
    }
}
