<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // validation
            "username" => ["required", "email", "max:100"],
            "password" => ["required", Password::min(6)->letters()->numbers()->symbols()]
        ];
    }

    // before validation
    protected function prepareForValidation(): void
    {
        // ingin melakukan sesuatu sebelum melakukan validasi, misal membersihkan data yang tidak dibutuhkan
        $this->merge([
            "username" => strtolower($this->input("username"))
        ]);
    }

    // after validation
    protected function passedValidation(): void
    {
        // melakukan sesuai sesudah validasi,
        $this->merge([
            "password" => bcrypt($this->input("password"))
        ]);
    }
}
