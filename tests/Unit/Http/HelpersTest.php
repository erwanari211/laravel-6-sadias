<?php

namespace Tests\Unit\Http;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_return_string_given_to_parameter()
    {
        $string = $this->faker->sentence;
        $stringFromHelper = example_function($string);
        $this->assertEquals($string, $stringFromHelper);
        $this->assertTrue($string === $stringFromHelper);
    }
}
