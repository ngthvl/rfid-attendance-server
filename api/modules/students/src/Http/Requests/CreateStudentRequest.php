<?php

namespace Tamani\Students\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStudentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_person' => 'required',
            'contact_number' => 'required',
            'contact_address' => 'required',
        ];
    }
}
