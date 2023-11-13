<?php

namespace Tamani\RfidTerminal\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tamani\Admin\Models\Admin;
use Tamani\RfidTerminal\Models\RfidTerminal;

class TerminalResource extends JsonResource
{
    /*
     * @var Tamani\Admin\Models\Admin
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->resource->only(array_merge(RfidTerminal::FILLABLE, [
            'id', 'created_at'
        ]));
    }
}
