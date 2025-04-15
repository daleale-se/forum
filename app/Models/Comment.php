<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'temporal_user_id',
        'body',
    ];

    // Each comment belongs to a Thread
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    // Each comment belongs to a Temporal User
    public function temporalUser()
    {
        return $this->belongsTo(TemporalUser::class);
    }
}
