<?php

namespace Tamani\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tamani\Admin\Models\Admin;

class AdminProfileResource extends JsonResource
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
        return $this->resource->only(array_merge(Admin::FILLABLE, [
            'id', 'created_at'
        ]));
    }
}
