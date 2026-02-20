<?php

namespace App\Services;

use App\Models\Resolution;

class ResolutionService
{
    public function index()
    {
        return Resolution::with('agendaItem', 'votes.user')->get();
    }

    public function create(array $data): Resolution
    {
        return Resolution::create($data);
    }

    public function show(Resolution $resolution): Resolution
    {
        return $resolution->load("agendaItem",'votes.user');
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
