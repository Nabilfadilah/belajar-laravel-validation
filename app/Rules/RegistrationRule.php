<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class RegistrationRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{
    private array $data;
    private Validator $validator;

    // setData untuk override datanya
    public function setData(array $data): RegistrationRule
    {
        $this->data = $data; // simpan data
        return $this;
    }

    // setValidator untuk terima data validator
    public function setValidator(Validator $validator): RegistrationRule
    {
        $this->validator = $validator; // akan dikirim lewat sini
        return $this;
    }

    // custome Rules yang bisa ambil semua data dan bisa compare semua datanya 
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = $value; // ambil data dari value
        $username = $this->data['username']; // ambil data dari data username

        // jika password dan username sama 
        if ($password == $username) {
            // tampilkan message ini
            $fail("$attribute must be different with username");
        }
    }
}
