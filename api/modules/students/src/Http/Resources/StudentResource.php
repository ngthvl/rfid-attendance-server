<?php

namespace Tamani\Students\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tamani\Students\Models\Student;

class StudentResource extends JsonResource
{
    /** @var Student */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'contact_person' => $this->contact_person,
            'contact_number' => $this->contact_number,
            'contact_address' => $this->contact_address,
            'created_at' => $this->created_at,
            'avatar' => $this->avatar,
            'rfid_tag' => $this->activeRfidTag(),
        ];
    }
}
