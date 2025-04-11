<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporalUser extends Model
{
    use HasFactory;

    protected $fillable = ['thread_id', 'assigned_username'];

    public function thread(){
        return $this->belongsTo(Thread::class);
    }

}
