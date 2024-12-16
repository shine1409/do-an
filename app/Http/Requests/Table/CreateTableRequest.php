<?php

namespace App\Http\Requests\Table;

use Illuminate\Foundation\Http\FormRequest;

class CreateTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number' => 'required|integer|min:0|unique:tables,number',
            'seats' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'number.required' => 'Số là bắt buộc.',
            'number.integer' => 'Số phải là số nguyên.',
            'number.min' => 'Số không được là số âm.',
            'number.unique' => 'Số bàn đã tồn tại.',
            'seats.required' => 'Số ghế là bắt buộc.',
            'seats.integer' => 'Số ghế phải là số nguyên.',
            'seats.min' => 'Số ghế không được là số âm.',
        ];
    }

}
