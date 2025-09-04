<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ValidatorTest extends TestCase
{
    // test validator
    public function testValidator(): void
    {
        // variable data, yg isinya array  
        $data = [
            "username" => "admin",
            "password" => "12345",
        ];

        $rules  = [
            "username" => "required",
            "password" => "required",
        ];

        // lakukan validator
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator); // hasilnya 

        // menjalankan validasi
        self::assertTrue($validator->passes()); // passes(), akan mengembalikan true jika sukses, false jika gagal
        self::assertFalse($validator->fails()); // fails(), akan mengembalikan true jika gagal, false jika sukses
    }

    public function testInvalidValidator(): void
    {
        // variable data, yg isinya array  
        $data = [
            "username" => "",
            "password" => "",
        ];

        $rules  = [
            "username" => "required",
            "password" => "required",
        ];

        // lakukan validator
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator); // hasilnya 

        // menjalankan validasi
        self::assertFalse($validator->passes()); // passes(), akan mengembalikan true jika sukses, false jika gagal
        self::assertTrue($validator->fails()); // fails(), akan mengembalikan true jika gagal, false jika sukses

        // mendapatkan detail dari error getMessageBag()
        $message = $validator->getMessageBag();
        // $message->get('username'); // keys() ->get(), mau lihat errornya apak aja, misalnya 'username' atau 'name', dll

        // translate jadi Json
        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }

    // validator exception
    public function testValidatorValidationException()
    {
        $data = [
            "username" => "",
            "password" => ""
        ];

        $rules = [
            "username" => "required",
            "password" => "required"
        ];

        // lakukan validator
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator); // hasilnya tidak boleh kosong

        // kondisi
        try {
            // saat kita lakukan validate
            $validator->validate();
            self::fail("ValidationException not thrown"); // fail(), akan mengembalikan true jika gagal, false jika sukses, dengan message custom
        } catch (ValidationException $exception) {
            self::assertNotNull($exception->validator); // ($exception->validator), dapatkan object dari validator 
            $message = $exception->validator->errors(); // $exception->validator->errors(), dapatkan error message nya
            // translate jadi Json
            Log::error($message->toJson(JSON_PRETTY_PRINT));
        }
    }

    // Validation Rules and Multiple Rules
    public function testValidatorMultipleRules()
    {
        App::setLocale("id"); // set zona id

        $data = [
            "username" => "abil",
            "password" => "abil"
        ];

        $rules = [
            "username" => "required|email|max:100", // validation Rules, tanda pagar |
            "password" => ["required", "min:6", "max:20"] // validation Rules, pake array
        ];

        // lakukan validator
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator); // hasilnya tidak boleh kosong

        // menjalankan validasi
        self::assertFalse($validator->passes());
        self::assertTrue($validator->fails());

        // mendapatkan detail dari error getMessageBag()
        $message = $validator->getMessageBag();

        // translate jadi Json
        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }

    // Valid Data
    public function testValidatorValidData()
    {
        // variable array
        $data = [
            "username" => "admin@pzn.com",
            "password" => "rahasia",
            "admin" => true, // input ini, tidak akan panggil
            "others" => "xxx" // input ini, tidak akan panggil
        ];

        // validasi
        $rules = [
            // karena di validasinya tidak tidak tambahkan key nya contoh admin/other
            "username" => "required|email|max:100",
            "password" => "required|min:6|max:20"
        ];

        // lakukan validator
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator); // hasilnya tidak boleh kosong

        try {
            // saat kita lakukan validate
            $valid = $validator->validate();
            Log::info(json_encode($valid, JSON_PRETTY_PRINT)); // tampilkan infonya 
        } catch (ValidationException $exception) {
            self::assertNotNull($exception->validator); // ($exception->validator), dapatkan object dari validator
            $message = $exception->validator->errors(); // $exception->validator->errors(), dapatkan error message nya
            // translate ke json
            Log::error($message->toJson(JSON_PRETTY_PRINT));
        }
    }

    // public function testValidatorInlineMessage()
    // {
    //     $data = [
    //         "username" => "eko",
    //         "password" => "eko"
    //     ];

    //     $rules = [
    //         "username" => "required|email|max:100",
    //         "password" => ["required", "min:6", "max:20"]
    //     ];

    //     $messages = [
    //         "required" => ":attribute harus diisi",
    //         "email" => ":attribute harus berupa email",
    //         "min" => ":attribute minimal :min karakter",
    //         "max" => ":attribute maksimal :max karakter",
    //     ];

    //     $validator = Validator::make($data, $rules, $messages);
    //     self::assertNotNull($validator);

    //     self::assertFalse($validator->passes());
    //     self::assertTrue($validator->fails());

    //     $message = $validator->getMessageBag();

    //     Log::info($message->toJson(JSON_PRETTY_PRINT));
    // }

    // public function testValidatorAdditionalValidation()
    // {
    //     $data = [
    //         "username" => "eko@pzn.com",
    //         "password" => "eko@pzn.com"
    //     ];

    //     $rules = [
    //         "username" => "required|email|max:100",
    //         "password" => ["required", "min:6", "max:20"]
    //     ];

    //     $validator = Validator::make($data, $rules);
    //     $validator->after(function (\Illuminate\Validation\Validator $validator){
    //         $data = $validator->getData();
    //         if($data['username'] == $data['password']){
    //             $validator->errors()->add("password", "Password tidak boleh sama dengan username");
    //         }
    //     });
    //     self::assertNotNull($validator);

    //     self::assertFalse($validator->passes());
    //     self::assertTrue($validator->fails());

    //     $message = $validator->getMessageBag();

    //     Log::info($message->toJson(JSON_PRETTY_PRINT));
    // }

    // public function testValidatorCustomRule()
    // {
    //     $data = [
    //         "username" => "eko@pzn.com",
    //         "password" => "eko@pzn.com"
    //     ];

    //     $rules = [
    //         "username" => ["required", "email", "max:100", new Uppercase()],
    //         "password" => ["required", "min:6", "max:20", new RegistrationRule()]
    //     ];

    //     $validator = Validator::make($data, $rules);
    //     self::assertNotNull($validator);

    //     self::assertFalse($validator->passes());
    //     self::assertTrue($validator->fails());

    //     $message = $validator->getMessageBag();

    //     Log::info($message->toJson(JSON_PRETTY_PRINT));
    // }

    // public function testValidatorCustomFunctionRule()
    // {
    //     $data = [
    //         "username" => "eko@pzn.com",
    //         "password" => "eko@pzn.com"
    //     ];

    //     $rules = [
    //         "username" => ["required", "email", "max:100", function(string $attribute, string $value, \Closure $fail){
    //             if(strtoupper($value) != $value){
    //                 $fail("The field $attribute must be UPPERCASE");
    //             }
    //         }],
    //         "password" => ["required", "min:6", "max:20", new RegistrationRule()]
    //     ];

    //     $validator = Validator::make($data, $rules);
    //     self::assertNotNull($validator);

    //     self::assertFalse($validator->passes());
    //     self::assertTrue($validator->fails());

    //     $message = $validator->getMessageBag();

    //     Log::info($message->toJson(JSON_PRETTY_PRINT));
    // }

    // public function testValidatorRuleClasses()
    // {
    //     $data = [
    //         "username" => "Eko",
    //         "password" => "eko@pzn123.com"
    //     ];

    //     $rules = [
    //         "username" => ["required", new In(["Eko", "Budi", "Joko"])],
    //         "password" => ["required", Password::min(6)->letters()->numbers()->symbols()]
    //     ];

    //     $validator = Validator::make($data, $rules);
    //     self::assertNotNull($validator);

    //     self::assertTrue($validator->passes());
    // }

    // public function testNestedArray()
    // {
    //     $data = [
    //         "name" => [
    //             "first" => "Eko",
    //             "last" => "Kurniawan"
    //         ],
    //         "address" => [
    //             "street" => "Jalan. Mangga",
    //             "city" => "Jakarta",
    //             "country" => "Indonesia"
    //         ]
    //     ];

    //     $rules = [
    //         "name.first" => ["required", "max:100"],
    //         "name.last" => ["max:100"],
    //         "address.street" => ["max:200"],
    //         "address.city" => ["required", "max:100"],
    //         "address.country" => ["required", "max:100"],
    //     ];

    //     $validator = Validator::make($data, $rules);
    //     self::assertTrue($validator->passes());

    // }

    // public function testNestedIndexedArray()
    // {
    //     $data = [
    //         "name" => [
    //             "first" => "Eko",
    //             "last" => "Kurniawan"
    //         ],
    //         "address" => [
    //             [
    //                 "street" => "Jalan. Mangga",
    //                 "city" => "Jakarta",
    //                 "country" => "Indonesia"
    //             ],
    //             [
    //                 "street" => "Jalan. Manggis",
    //                 "city" => "Jakarta",
    //                 "country" => "Indonesia"
    //             ]
    //         ]
    //     ];

    //     $rules = [
    //         "name.first" => ["required", "max:100"],
    //         "name.last" => ["max:100"],
    //         "address.*.street" => ["max:200"],
    //         "address.*.city" => ["required", "max:100"],
    //         "address.*.country" => ["required", "max:100"],
    //     ];

    //     $validator = Validator::make($data, $rules);
    //     self::assertTrue($validator->passes());

    // }
}
