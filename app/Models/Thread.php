<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'category',
        'temporal_user_id'
    ];

    public function temporalUser(){
        return $this->belongsTo(TemporalUser::class, 'temporal_user_id');
    }    

}
