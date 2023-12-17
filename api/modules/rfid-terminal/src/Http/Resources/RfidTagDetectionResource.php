<?php


namespace Tamani\RfidTerminal\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tamani\RfidTerminal\Models\RfidOutput;
use Tamani\RfidTerminal\Models\RfidTagAllocation;

class RfidTagDetectionResource extends JsonResource
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
        return $this->resource->only(array_merge(RfidOutput::FILLABLE, []));
    }
}
