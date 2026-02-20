<?php

namespace App\Http\Controllers\api;

use App\Models\AgendaItem;
use App\Services\AgendaItemService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class AgendaItemController
{
    use ApiResponse;
    public function __construct(
        protected AgendaItemService $service
    ) {}
    public function index() {
        return AgendaItem::with(['resolutions', 'votes'])->get();
    }
    
    public function show(AgendaItem $agendaItem)
    {
        // Egy konkrét elem megjelenítése szavazatokkal/határozatokkal
        $agendaItem->load(['resolutions', 'votes']); 
        return $this->success($agendaItem);
    }
    public function store(Request $request)
    {
        $this->authorize('create', AgendaItem::class);
        return $this->service->create($request->all());
    }
    public function update(Request $request, AgendaItem $agendaItem)
    {
        // $this->authorize('update', $agendaItem);
        // return $this->service->update($agendaItem, $request->all());
        try {
            $agendaItem->status = $request->status;
            $agendaItem->save();
            
            return response()->json($agendaItem->load("resolutions",'votes'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString() // Ez megmondja pontosan hol a hiba
            ], 500);
        }
    }
    public function destroy(AgendaItem $agendaItem)
    {
        // $this->authorize('delete', $agendaItem);
        $this->service->delete($agendaItem);
        return $this->noContent();
    }
}
