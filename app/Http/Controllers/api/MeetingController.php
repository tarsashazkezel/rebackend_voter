<?php

namespace App\Http\Controllers\api;

use App\Models\Meeting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Services\MeetingService;

class MeetingController extends Controller
{
    public function __construct(protected MeetingService $meetingService) {}
    
    public function create(MeetingRequest $request)
    {
        $validated = $request->validated();
        $meeting = $this->meetingService->create($validated);

        // JAVÍTÁS: Betöltjük a kapcsolatokat az új meetinghez is, 
        // hogy a frontend azonnal meg tudja jeleníteni a nullás adatokat is.
        return response()->json(
            $meeting->load(['agenda_items.resolutions.votes.user', 'present_users']), 
            201
        );
    }

    public function getMeetings() {
        return $this->formatMeetingsResponse(
            Meeting::with(['agenda_items.resolutions.votes.user'])->latest()->get()
        );
    }

    public function getMeeting(Meeting $meeting) {
        // Itt is le kell futtatni a számolást!
        return $this->formatMeetingsResponse(collect([$meeting]))->first();
    }

    // Segédfüggvény, hogy ne kelljen kétszer megírni a logikát
    private function formatMeetingsResponse($meetings) {
        $meetings->each(function($meeting) {
            $voters = collect();
            foreach($meeting->agenda_items as $item) {
                foreach($item->resolutions as $res) {
                    foreach($res->votes as $vote) {
                        if($vote->user) $voters->push($vote->user);
                    }
                }
            }
            $meeting->setRelation('present_users', $voters->unique('id')->values());
        });
        return $meetings;
    }
    public function update(Meeting $meeting, UpdateMeetingRequest $request)
    {
        $validated = $request->validated();
        return $this->meetingService->update($meeting, $validated);
    }

    public function delete(Meeting $meeting)
    {
        try {
            $this->meetingService->delete($meeting);
            
            return response()->json([
                'success' => true,
                'message' => 'Közgyűlés sikeresen törölve'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt: ' . $e->getMessage()
            ], 500);
        }
    }
}