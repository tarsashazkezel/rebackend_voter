<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'resolution_id',
        'user_id',
        'vote',
    ];

    public function resolution()
    {
        return $this->belongsTo(Resolution::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
