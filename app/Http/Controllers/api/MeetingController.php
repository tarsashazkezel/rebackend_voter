<?php

namespace App\Http\Controllers\api;

use App\Models\Meeting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Services\MeetingService;
use App\Resources\MeetingResource;

class MeetingController extends Controller
{
    public function __construct(protected MeetingService $meetingService) {
        $this->middleware(function ($request, $next) {
        if (auth()->check() && !auth()->user()->is_active) {
            return response()->json(['message' => 'Fiók letiltva.'], 403);
        }
        return $next($request);
    });
    }
    
    public function create(MeetingRequest $request)
    {
        $validated = $request->validated();
        $meeting = $this->meetingService->create($validated);
        return response()->json(
            $meeting->load(['agenda_items.resolutions.votes.user', 'present_users']), 
            201
        );
    }

    public function getMeetings() {
        return response()->json(
            Meeting::with(['agenda_items.resolutions.votes.user', 'present_users','agenda_items.resolutions.user'])->latest()->get()
        );
        // return response()->json(
        //   MeetingResource::collection( Meeting::with(['agenda_items.resolutions.votes.user', 'present_users','agenda_items.resolutions.user'])->latest()->get()),
        // );
    }

    public function getMeeting(Meeting $meeting) {
        // Itt is le kell futtatni a számolást!
        return response()->json($meeting->load(
            ['agenda_items.resolutions.votes.user',
             'present_users'
             ])
        );
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
    public function attend(Meeting $meeting) 
    {
        $user = auth()->user();
        if ($user->ownership_ratio <= 0) {
            return response()->json(['message' => '0 TH-val nem vehet részt',"adat"=>$user], 403);
        }
        
        // Ez rögzíti a júzert a meeting_user táblába
        $meeting->present_users()->syncWithoutDetaching([$user->id]);
        return response()->json(['success' => true]);
    }   

    public function toggleRepeated(Meeting $meeting) {
        $meeting->update(['is_repeated' => !$meeting->is_repeated]);
        return response()->json($meeting);
}
}