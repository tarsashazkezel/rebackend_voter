<?php

namespace App\Services;
use App\Models\AgendaItem;

class AgendaItemService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {    }
    public function create(array $data)
    {
        return AgendaItem::create($data);
    }

    public function update(AgendaItem $item, array $data)
    {
        $item->update($data);
        return $item;
    }

    public function delete(AgendaItem $item): void
    {
        $item->delete();
    }

}
