<?php

namespace App\Services;

use App\Models\AgendaItem;
use App\Models\Resolution;
use Exception;

class ResolutionService
{
    public function index()
    {
        return Resolution::with('agendaItem', 'votes.user')->get();
    }

    public function create(array $data): Resolution{
        $userId = (new Userservice())->getUserId($data['username']);

        if (!$userId) {
            throw new Exception("Nem található felhasználó ezzel a névvel: " . $data['username']);
        }
        $resolution= new Resolution;
        $resolution->agenda_item_id = $data['agenda_item_id'];
        $resolution->text = $data['text'];
        $resolution->user_id = $userId;
        $resolution->save();
        return $resolution;
    }

    public function show(Resolution $resolution): Resolution
    {
        return $resolution->load("agendaItem");
    }

    public function update(Resolution $resolution, array $data): Resolution
    {
        $resolution->update($data);
        return $resolution;
    }

    public function delete(Resolution $resolution): void
    {
        $resolution->delete();
    }

}
