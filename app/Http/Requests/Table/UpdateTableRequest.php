<?php

namespace App\Http\Requests\Table;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTableRequest extends FormRequest
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
        // Lấy ID của bảng từ URL

        $id= $this->route('id');

        // Quy tắc xác thực cho các trường trong yêu cầu
        return [
            'number' => 'required|integer|min:0|unique:tables,number,' . $id,
            'seats' => 'required|integer|min:0',
        ];
    }

    /**
     * Get the validation messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        // Các thông báo lỗi tùy chỉnh
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
