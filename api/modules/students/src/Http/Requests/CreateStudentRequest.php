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
//        |regex:/^(09|\+639)\d{9}/$
        return [
            'student_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_person' => 'required',
            'contact_number' => 'required|digits_between:10,13',
            'contact_address' => 'required',
            'avatar' => 'nullable|url',
            'section' => 'array|nullable',
            'level' => 'array|nullable',
            'section.id' => 'required|numeric',
            'level.id' => 'required|numeric',
        ];
    }
}
