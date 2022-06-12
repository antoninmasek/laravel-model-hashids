<?php

namespace AntoninMasek\Hashids;

class Hashids
{
    public function make(string $salt = null, int $min_length = null, string $alphabet = null): \Hashids\Hashids
    {
        return new \Hashids\Hashids(
            $salt ?? config('hashids.salt'),
            $min_length ?? config('hashids.min_length'),
            $alphabet ?? config('hashids.alphabet'),
        );
    }
}
