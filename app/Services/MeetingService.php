<?php

namespace App\Services;

use App\Models\Meeting;
use App\Models\AgendaItem;
use App\Models\Resolution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MeetingService
{
    public function create(array $data): Meeting
    {
        return DB::transaction(function () use ($data) {
            // 1. Közgyűlés létrehozása
            $meeting = Meeting::create([
                'title' => $data['title'],
                'meeting_date' => $data['meeting_date'],
                'location' => $data['location'],
                'created_by' => Auth::id(),
                'is_repeated' => false
            ]);

            // 2. Napirendi pontok és határozatok mentése
            foreach ($data['agenda_items'] as $itemData) {
                
                // Közvetlenül a Model-t hívjuk a stabilitás miatt
                $item = AgendaItem::create([
                    'meeting_id' => $meeting->id,
                    'title' => $itemData['title'],
                    'description' => $itemData['description'] ?? null,
                    'status' => 'PENDING'
                ]);

                // Határozat létrehozása user_id NÉLKÜL
                // Így a frontend szavazható javaslatnak látja, nem felszólalásnak
                Resolution::create([
                    'agenda_item_id' => $item->id,
                    'text' => $itemData['resolution_text'],
                    'requires_unanimous' => false,
                    'user_id' => null, 
                ]);
            }

            return $meeting;
        });
    }

    public function show(Meeting $meeting): Meeting
    {
        return $meeting->load([
            'creator',
            'agenda_items.resolutions.votes.user',
            'present_users',
        ]);
    }

    public function update(Meeting $meeting, array $data): Meeting
    {
        $meeting->update([
            'title' => $data['title'],
            'meeting_date' => $data['meeting_date'],
            'location' => $data['location'],
        ]);
        return $meeting->load(['agenda_items.resolutions.votes']);
    }

    public function delete(Meeting $meeting): void
    {
        $meeting->delete();
    }
}