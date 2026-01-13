<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreScdYearRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add proper authorization logic if needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'year' => 'required|string|size:4|unique:scd_years,year',
            'created_date' => 'required|date',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'year.required' => 'กรุณากรอกปี',
            'year.size' => 'ปีต้องเป็นตัวเลข 4 หลัก',
            'year.unique' => 'ปีนี้มีอยู่ในระบบแล้ว',
            'created_date.required' => 'กรุณาเลือกวันที่สร้าง',
            'created_date.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
