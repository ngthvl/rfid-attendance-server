<?php

namespace Tamani\Students\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMultipleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.*.student_id' => 'required',
            'data.*.first_name' => 'required',
            'data.*.last_name' => 'required',
            'data.*.contact_person' => 'required',
            'data.*.contact_number' => 'required',
            'data.*.contact_address' => 'nullable',
            'data.*.avatar' => 'nullable|url',
            'section' => 'array|nullable',
            'level' => 'array|nullable',
            'section.id' => 'required|numeric',
            'level.id' => 'required|numeric',
        ];
    }
}
