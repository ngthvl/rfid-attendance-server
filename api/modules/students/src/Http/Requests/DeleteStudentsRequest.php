<?php

namespace Tamani\Students\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class DeleteStudentsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids' => [
                'required',
                'array'
            ],
            'ids.*' => [
                'uuid',
                'required'
            ]
        ];
    }
}
