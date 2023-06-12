<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Procedure;

class Task extends Model
{
    use HasFactory;
        /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'is_visible',
    ];
    
    public function procedures()
    {
        return $this->hasMany(Procedure::class);
    }
}
