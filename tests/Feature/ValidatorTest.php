<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
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
    }
}
