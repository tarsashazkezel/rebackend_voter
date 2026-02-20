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
        return $this->meetingService->create($validated);
    }
    
    public function getMeeting(Meeting $meeting)
    {
        return $this->meetingService->show($meeting);
    }

    public function getMeetings(){
        // Fontos a kapcsolatok betöltése, hogy az Admin Dashboard lássa a pontokat
        return Meeting::with(['agenda_items.resolutions.votes'])->orderBy('created_at', 'desc')->get();
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