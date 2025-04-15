<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TemporalUser extends Model
{
    use HasFactory;

    protected $fillable = ['assigned_username'];

    public function threads(){
        return $this->hasMany(Thread::class, 'temporal_user_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public static function generateUsername(): string{
        return 'user' . Str::random(6);
    }

}
