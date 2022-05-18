<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
    'id', 'name', 'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
