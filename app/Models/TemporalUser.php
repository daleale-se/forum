<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporalUser extends Model
{
    use HasFactory;

    protected $fillable = ['assigned_username'];

    public function threads(){
        return $this->hasMany(Thread::class, 'temporal_user_id');
    }

}
