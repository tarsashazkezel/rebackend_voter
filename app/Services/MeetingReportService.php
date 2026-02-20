<?php

namespace App\Services;

use App\Models\Meeting;

class MeetingReportService
{
    /**
     * Create a new class instance.
     */
    public function generate(Meeting $meeting): array
    {
        $meeting->load([
            'creator',
            'agendaItems.resolutions.votes.user',
        ]);

        return [
            'meeting' => $this->meetingMeta($meeting),
            'agenda_items' => $this->agendaItems($meeting),
        ];
    }

    protected function meetingMeta(Meeting $meeting): array
    {
        return [
            'title' => $meeting->title,
            'date' => $meeting->meeting_date,
            'location' => $meeting->location,
            'created_by' => $meeting->creator->name,
        ];
    }

    protected function agendaItems(Meeting $meeting): array
    {
        return $meeting->agendaItems->map(function ($item) {
            return [
                'title' => $item->title,
                'description' => $item->description,
                'resolutions' => $this->resolutions($item),
            ];
        })->toArray();
    }

    protected function resolutions($agendaItem): array
    {
        $voteService = app(VoteService::class);

        return $agendaItem->resolutions->map(function ($resolution) use ($voteService) {
            return [
                'text' => $resolution->text,
                'requires_unanimous' => $resolution->requires_unanimous,
                'result' => $voteService->calculateResult($resolution),
                'votes' => $this->votes($resolution),
            ];
        })->toArray();
    }

    protected function votes($resolution): array
    {
        return $resolution->votes->map(function ($vote) {
            return [
                'voter' => $vote->user->name,
                'ownership_ratio' => $vote->user->ownership_ratio,
                'vote' => $vote->vote,
            ];
        })->toArray();
    }
}
