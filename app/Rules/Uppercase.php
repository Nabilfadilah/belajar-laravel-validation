<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Uppercase implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // uppercase rule
        if ($value !== strtoupper($value)) {
            // tinggal kasih messagenya, jika gagal
            // $fail("The $attribute must be UPPERCASE");

            // $fail, akan ambil dari file validation php(validation), dan nama attribute (.custom.uppercase)
            $fail("validation.custom.uppercase")
                ->translate([ // translate
                    "attribute" => $attribute, // kirim data attribute(key)
                    "value" => $value // kirim data value
                    // jadi tinggal pake (: titik dua) attribute
                ]);
        }
    }
}
