<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vote;
use App\Services\VoteService;

class VoteController extends Controller
{
    public function __construct(protected VoteService $voteService) {}
    
    public function getVotes(){
        return response()->json($this->voteService.getAllVotes());    
    }
    public function create(Request $request) {
        $validated = $request->validate([
            'resolution_id' => 'required|exists:resolutions,id',
            'vote' => 'required|in:yes,no,abstain'
        ]);

        $vote = $this->voteService->castVote($validated);

        if (!$vote) {
            return response()->json(['message' => 'Már szavazott ezen a ponton!'], 403);
        }

        return response()->json(['message' => 'Sikeres szavazat!', 'vote' => $vote], 201);

    }
    public function getVote(Vote $vote) {
        return response()->json($vote->load(['user', 'resolution']));
    }

   public function update(Request $request, Vote $vote) {
        $validated = $request->validate([
            'vote' => 'required|in:yes,no,abstain'
        ]);

        $updatedVote = $this->voteService->updateVote($vote, $validated);

        return response()->json(['message' => 'Szavazat frissítve!', 'vote' => $updatedVote]);
    }
    public function destroy(Vote $vote) {
        $this->voteService->deleteVote($vote);
        return response()->json(['message' => 'Szavazat törölve!']);
    }
}