<?php

namespace AntoninMasek\Hashids;

class Hashids
{
    private ?string $salt = null;

    private ?string $alphabet = null;

    private ?int $min_length = null;

    public function salt(string $salt = null): static
    {
        $clone = clone $this;

        $clone->salt = $salt;

        return $clone;
    }

    public function alphabet(string $alphabet = null): static
    {
        $clone = clone $this;

        $clone->alphabet = $alphabet;

        return $clone;
    }

    public function minLength(int $minLength = null): static
    {
        $clone = clone $this;

        $clone->min_length = $minLength;

        return $clone;
    }

    /**
     * @param  array<int>|int  $numbers
     * @return string
     */
    public function encode(array|int $numbers): string
    {
        return $this->getHashidsGenerator()
            ->encode($numbers);
    }

    /**
     * @param  string  $hash
     * @return array<int>
     */
    public function decode(string $hash): array
    {
        return $this->getHashidsGenerator()
            ->decode($hash);
    }

    private function getHashidsGenerator(): \Hashids\Hashids
    {
        $parameters = array_filter([
            'salt' => $this->salt ?? config('hashids.salt'),
            'minHashLength' => $this->min_length ?? config('hashids.min_length'),
            'alphabet' => $this->alphabet ?? config('hashids.alphabet'),
        ]);

        return new \Hashids\Hashids(...$parameters);
    }
}
