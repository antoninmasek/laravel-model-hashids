<?php

namespace AntoninMasek\Hashids\Tests;

use AntoninMasek\Hashids\Facades\Hashids;

class HashidsTest extends TestCase
{
    public function testItCanEncodeNumber()
    {
        $encodedValue = Hashids::encode(1);

        $this->assertEquals('jR', $encodedValue);
    }

    public function testItCanEncodeArrayOfNumbers()
    {
        $encodedValue = Hashids::encode([1, 2, 3]);

        $this->assertEquals('o2fXhV', $encodedValue);
    }

    public function testItIsNotPossibleToEncodeNull()
    {
        $this->expectError();

        Hashids::encode(null);
    }

    public function testItCanEncodeWithSalt()
    {
        $encodedValue = Hashids::salt('test')
            ->encode(1);

        $this->assertEquals('gp', $encodedValue);
    }

    public function testItCanEncodeWithMinLength()
    {
        $encodedValue = Hashids::minLength(5)
            ->encode(1);

        $this->assertTrue(strlen($encodedValue) >= 5);
    }

    public function testItCanEncodeWithAlphabet()
    {
        $alphabet = '1234567890qwertz';

        $encodedValue = Hashids::alphabet($alphabet)
            ->encode(1);

        $containsInvalidValue = collect(str_split($encodedValue))->contains(function ($char) use ($alphabet) {
            return ! str($alphabet)->contains($char);
        });

        $this->assertFalse($containsInvalidValue);
    }

    public function testItCanDecode()
    {
        $decodedValue = Hashids::decode('jR');

        $this->assertEquals([1], $decodedValue);
    }

    public function testItCanDecodeWithSalt()
    {
        $value = 1;
        $generator = Hashids::salt('test');

        $this->assertEquals(
            [$value],
            $generator->decode($generator->encode($value)),
        );
    }

    public function testItCanDecodeWithMinLength()
    {
        $value = 1;
        $generator = Hashids::minLength(5);

        $this->assertEquals(
            [$value],
            $generator->decode($generator->encode($value)),
        );
    }

    public function testItCanDecodeWithAlphabet()
    {
        $value = 1;
        $generator = Hashids::alphabet('1234567890qwertz');

        $this->assertEquals(
            [$value],
            $generator->decode($generator->encode($value)),
        );
    }
}
