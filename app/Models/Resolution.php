<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    protected $fillable = [
        'agenda_item_id',
        'text',
        'requires_unanimous',
    ];

    protected $casts = [
        'requires_unanimous' => 'boolean',
    ];

    public function agendaItem()
    {
        return $this->belongsTo(AgendaItem::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
     public function getResultsAttribute() {
        return [
            'yes' => $this->votes()->where('vote', 'yes')->join('users', 'votes.user_id', '=', 'users.id')->sum('users.ownership_ratio'),
            'no' => $this->votes()->where('vote', 'no')->join('users', 'votes.user_id', '=', 'users.id')->sum('users.ownership_ratio'),
            'abstain' => $this->votes()->where('vote', 'abstain')->join('users', 'votes.user_id', '=', 'users.id')->sum('users.ownership_ratio'),
        ];
        foreach ($this->votes as $vote) {
            $ratio = $vote->user->ownership_ratio;
            $results[$vote->vote] += $ratio;
            $results['total_voted_ratio'] += $ratio;
        }

        return $results;
    
    }
}
