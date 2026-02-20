<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\Resolution;
use App\Services\ResolutionService;
use App\Traits\ApiResponse;
use Exception;

class ResolutionController
{
    use ApiResponse;
    public function __construct(
        protected ResolutionService $service
    ) {}

    public function index(){
        return $this->service->index();
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'comment' => 'required|string', 
                'agenda_item_id' => 'required|exists:agenda_items,id',
            ]);

            // JAVÍTÁS: Az adatbázisodban 'text' mező van! 
            // A 'comment'-et vagy a 'title'-t (vagy a kettőt összefűzve) a 'text' mezőbe kell menteni.
            return $this->service->create([
                'text' => $validated['comment'], // Az adatbázis 'text' oszlopába mentjük
                'agenda_item_id' => $validated['agenda_item_id'],
            ]);
        } catch (Exception $e) {
            // Így látni fogod a pontos hibaüzenetet a válaszban, ha még mindig elszáll
            return response()->json(['message' => 'Hiba történt', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Resolution $resolution)
    {
        return $this->service->show($resolution);
    }

    public function update(Request $request, Resolution $resolution)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'comment' => 'sometimes|required|string',
                'status' => 'sometimes|string'
            ]);

            $updateData = [];
            if (isset($validated['comment'])) {
                $updateData['text'] = $validated['comment']; // Itt is 'text'-re javítva
            }

            return $this->service->update($resolution, $updateData);
        } catch (Exception $e) {
            return response()->json(['message' => 'Hiba történt', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Resolution $resolution)
    {
        $this->service->delete($resolution);
        return $this->noContent();
    }
}