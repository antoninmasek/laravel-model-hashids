<?php

namespace AntoninMasek\Hashids\Tests;

use AntoninMasek\Hashids\Facades\Hashids;

class HashidsTest extends TestCase
{
    public function testItCanEncodeNumber()
    {
        $value = 1;

        $this->assertEquals(
            [$value],
            Hashids::decode(Hashids::encode($value)),
        );
    }

    public function testItCanEncodeArrayOfNumbers()
    {
        $value = [1, 2, 3];

        $this->assertEquals(
            $value,
            Hashids::decode(Hashids::encode($value)),
        );
    }

    public function testItIsNotPossibleToEncodeNull()
    {
        $this->expectError();

        Hashids::encode(null);
    }

    public function testItCanEncodeWithSalt()
    {
        $value = 1;
        $generator = Hashids::salt('test');

        $this->assertEquals(
            [$value],
            $generator->decode($generator->encode($value)),
        );
    }

    public function testItCanEncodeWithMinLength()
    {
        $value = 1;
        $length = 5;
        $generator = Hashids::minLength($length);
        $encodedValue = $generator->encode($value);

        $this->assertEquals(
            [$value],
            $generator->decode($encodedValue),
        );

        $this->assertTrue(strlen($encodedValue) >= $length);
    }

    public function testItCanEncodeWithAlphabet()
    {
        $value = 1;
        $alphabet = '1234567890qwertz';
        $generator = Hashids::alphabet($alphabet);
        $encodedValue = $generator->encode($value);

        $this->assertEquals(
            [$value],
            $generator->decode($encodedValue),
        );

        $containsInvalidValue = collect(str_split($encodedValue))->contains(function ($char) use ($alphabet) {
            return ! str($alphabet)->contains($char);
        });

        $this->assertFalse($containsInvalidValue);
    }
}
