<?php

namespace App\Services;

use App\Models\Meeting;
use App\Models\AgendaItem;
use App\Models\Resolution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MeetingService
{
    private $agendaData = [];
    private $resolutionData = [];

    public function __construct(
        protected AgendaItemService $agendaItemService,
        protected ResolutionService $resolutionService
    ){}

    public function create(array $data): Meeting
    {
        return DB::transaction(function () use ($data) {

            $meeting = Meeting::create([
                'title' => $data['title'],
                'meeting_date' => $data['meeting_date'],
                'location' => $data['location'],
                'created_by' => Auth::id(),
            ]);

            foreach ($data['agenda_items'] as $itemData) {

                $item = $this->agendaItemService->create($this->fillAgendaData($meeting->id,$itemData));

                $this->resolutionService->create($this->fillResolutionData($item->id,$itemData));
            }

            return $meeting;
        });
    }

    public function show(Meeting $meeting): Meeting
    {
        return $meeting->load([
            'creator',
            'agenda_items.resolutions.votes.user',
        ]);
    }

    public function update(Meeting $meeting, array $data): Meeting
    {
        $meeting->update($data);
        return $meeting;
    }

    public function delete(Meeting $meeting): void
    {
        $meeting->delete();
    }

    protected function fillAgendaData($id,$data)
    {
        $this->agendaData=[
                    'meeting_id' => $id,
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                    'status' => 'PENDING'
                ];
        return $this->agendaData;
    }
    protected function fillResolutionData($id,$data)
    {
        $this->resolutionData=[
                    'agenda_item_id' => $id,
                    'text' => $data['resolution_text'],
                    'requires_unanimous' => false
                ];
        return $this->resolutionData;
    }
    protected function fillAgendaItems($meeting){
        foreach ($data['agenda_items'] as $itemData) {

                $item = AgendaItemService::create($this->fillAgendaData($meeting->id,$itemData));

            ResolutionService::create($this->fillResolutionData($item->id,$itemData));
        }
    }
}
