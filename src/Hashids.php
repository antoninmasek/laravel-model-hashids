<?php

namespace AntoninMasek\Hashids;

class Hashids
{
    public function __construct(private ?string $salt = null, private ?string $alphabet = null, private ?int $min_length = null)
    {
        $this->salt ??= config('hashids.salt');
        $this->alphabet ??= config('hashids.alphabet');
        $this->min_length ??= config('hashids.min_length');
    }

    public function salt(string $salt): static
    {
        $clone = clone $this;

        $clone->salt = $salt;

        return $clone;
    }

    public function alphabet(string $alphabet): static
    {
        $clone = clone $this;

        $clone->alphabet = $alphabet;

        return $clone;
    }

    public function minLength(int $minLength): static
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
        return new \Hashids\Hashids(
            $this->salt,
            $this->min_length,
            $this->alphabet
        );
    }
}
