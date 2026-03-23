<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    // FONTOS: A user_id-nak benne kell lennie a fillable tömbben!
    protected $fillable = [
        'agenda_item_id',
        'text',
        'requires_unanimous',
        'user_id', 
    ];

    protected $casts = [
        'requires_unanimous' => 'boolean',
    ];

    public function agendaItem() {
        return $this->belongsTo(AgendaItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }
}