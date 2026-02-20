<?php

namespace App\Services;

use App\Models\Resolution;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class VoteService
{
    // public function vote(User $user, Resolution $resolution, string $vote): Vote
    // {
    //     return Vote::create([
    //         'user_id' => $user->id,
    //         'resolution_id' => $resolution->id,
    //         'vote' => $vote,
    //     ]);
    // }
    //  public function calculateResult(Resolution $resolution): array{
    //     $votes = $resolution->votes()->with('user')->get();

    //     $yes = $no = $abstain = 0;

    //     foreach ($votes as $vote) {
    //         $weight = $vote->user->ownership_ratio;

    //         match ($vote->vote) {
    //             'yes' => $yes += $weight,
    //             'no' => $no += $weight,
    //             'abstain' => $abstain += $weight,
    //         };
    //     }

    //     $total = $yes + $no;

    //     return [
    //         'yes' => $yes,
    //         'no' => $no,
    //         'abstain' => $abstain,
    //         'total' => $total,
    //         'accepted' => $this->isAccepted($resolution, $yes, $no),
    //     ];
    // }
    // /**
    //  * Határozat elfogadása
    //  */
    // private function isAccepted(Resolution $resolution, float $yes, float $no): bool
    // {
    //     if ($resolution->requires_unanimous) {
    //         return $no == 0 && $yes > 0;
    //     }

    //     return $yes > $no;
    // }

    // /**
    //  * Szavazhat-e a felhasználó
    //  */
    // public function canUserVote(User $user): bool
    // {
    //     return $user->canVote();
    // }
    public function getAllVotes(): Collection
    {
        return Vote::with(['user', 'resolution'])->get();
    }

    /**
     * Új szavazat leadása logikai ellenőrzéssel.
     */
    public function castVote(array $data): ?Vote
    {
        $userId = Auth::id();
        $exists = Vote::where('user_id', $userId)
            ->where('resolution_id', $data['resolution_id'])
            ->exists();

        if ($exists) {
            return null;
        }

        return Vote::create([
            'user_id' => $userId,
            'resolution_id' => $data['resolution_id'],
            'vote' => $data['vote']
        ]);
    }
    public function updateVote(Vote $vote, array $data): Vote
    {
        $vote->update($data);
        return $vote;
    }
    public function deleteVote(Vote $vote): bool
    {
        return $vote->delete();
    }
}
