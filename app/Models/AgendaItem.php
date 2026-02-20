<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgendaItem extends Model
{
     protected $fillable = [
        'meeting_id',
        'title',
        'description',
    ];
    
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function resolutions() {
    return $this->hasMany(Resolution::class);
    }
    
    // Ha a szavazatokat közvetlenül az AgendaItem-en keresztül akarod elérni:
    public function votes() {
        return $this->hasManyThrough(Vote::class, Resolution::class);
    }
}
