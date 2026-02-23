<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    protected $table = 'meetings';
   protected $fillable = [
        'title',
        'meeting_date',
        'location',
        'created_by',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agenda_items(): HasMany
    {
        return $this->hasMany(AgendaItem::class, 'meeting_id', 'id');
    }
    public function present_users()
    {
        // return $this->hasMany(User::class, 'id', 'id')->whereRaw('1 = 0');
        //     return User::whereHas('votes.resolution.agendaItem', function($query) {
        //     $query->where('meeting_id', $this->id);
        // })->get();
            // Ez a legegyszerűbb módja, hogy a szavazókat visszakapd kapcsolatként
    return $this->hasManyThrough(
        User::class,
        Vote::class,
        'resolution_id', // votes táblán a kapcsoló (ez trükkös hasManyThrough-nál)
        'id',            // users táblán az id
        'id',            // meetings táblán az id
        'user_id'        // votes táblán a user_id
    )->distinct();
    }
}
